import asyncio
from aiohttp import ClientSession
import re
from bs4 import BeautifulSoup
import urllib.request
import random
from datetime import datetime
startTime = datetime.now()
import csv
import time
from urllib.parse import urljoin, urldefrag
import sys

# - a list of urls/ domains
# - Max requests for each url (by domain)
# - Max crawl depth for each url
# - Search pattern (regex)
# - Wait time between requests (by domain)
# Output:
# - Either a delimited list or import/ input to a database
# -- one row for each domain from the input list
# --- url
# --- pattern (if not found, empty)
# --- url pattern was found on (if not found, empty)

scriptnum = sys.argv[1]

url_list = []
file = open('majestic/majestic10k/output_10k_'+scriptnum,'a',1)
headers = {
    'User-Agent': 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.115 Safari/537.36'
}

result_list = []
seen = []
#regex_input = input("Enter your regex: ")
#csv_file = input("Enter csv file name: ")

#waiting_time = input("Please enter waiting time(In second): ")
#max_depth = input("Please enter max search depth: ")
#max_request = input("Please enter max request: ")
#regex_input = '/wp-content/themes/[^\r\n"\' ]+'
regex_input = 'content="WordPress[^\n\r"]+"'

#regex_input = 'about: keyword research. It'
csv_file = 'majestic/majestic10k/'+scriptnum

print("running ",scriptnum)

waiting_time = .5
max_depth = 2
max_request = 3


if not regex_input: regex_input = 'wp-content/themes[^" \n\r]+'

if csv_file:
    with open('%s'%csv_file, 'r') as f:
        reader = csv.reader(f, delimiter=',')
        for row in reader:
            url_list.append(row[0])

if waiting_time: 
    waiting_time = int(waiting_time)
else:
    waiting_time = None

if max_depth:
    max_depth = int(max_depth)
else:
    max_depth = 1

if max_request: 
    max_request = int(max_request)
else:
    max_request = 100


#filename_input = input("Enter your regex")
#wp-content/themes[^" \n\r]+

async def fetc_data(url,regex):
    print("Fetching url(Top Level):",url)
    file.write("\n"+url+"~")
    seen=[]
    seen.append(url)
    async with ClientSession(headers=headers) as session:
        async with session.get(url, headers=headers,timeout=4) as response:
            response = await response.read()
            obj = re.search(r'%s'%regex,response.decode('utf-8'))
            result = {}
            link = url
            try:
#                result["Pattern"] = regex
                endstrf = ''
                endstrf+="~Found~"+','.join([obj.group()])+"~"
                file.write(endstrf)

                if obj.group():
                    return
            except Exception as e:
                pass

            if max_depth == 2:
                soup = BeautifulSoup(response.decode('utf-8'),"html.parser") 
                print("Number of url found in second level",len(soup.find_all('a', href=True)))
                req = 0
                urls = list(set(soup.find_all('a', href=True)))
                for link in urls:
                    if str(urljoin(url, remove_fragment(link["href"]))) not in seen and str(url) in str(urljoin(url, remove_fragment(link["href"]))) and req <= max_request and '.mp3' not in str(link["href"]):
#                       removed waiting_time
                        req = req + 1
                        link = str(urljoin(url, remove_fragment(link["href"])))
                        if waiting_time:
                            time.sleep(waiting_time)

                        print("Crawling now(2nd Level): ",link)
                        seen.append(link)
                        async with session.get(link, headers=headers,timeout=4) as response:
                            response = await response.read()
                            obj = re.search(r'%s'%regex,response.decode('utf-8'))
                            result = {}
                            try:
                                if obj.group():
                                    return
                            except Exception as e:
                                pass

            if max_depth == 3:
                soup = BeautifulSoup(response.decode('utf-8'),"html.parser")
                print("Number of url found in Second level", len(soup.find_all('a', href=True)))
                req = 0 
                urls = list(set(soup.find_all('a', href=True)))
                for link in urls:
                    print("depth3")
                    if waiting_time:
                        time.sleep(waiting_time)                    
                    if url in str(link["href"]) and req <= max_request and '.mp3' not in str(link["href"]):
                        req = req + 1
                        link = link["href"]
                        async with session.get(link, headers=headers) as response:
                            print("Crawling now(Second Level): ",link)
                            response = await response.read()
                            obj = re.search(r'%s'%regex,response.decode('utf-8'))
                            result = {}
                            try:
                                if obj.group():
                                    return
                            except Exception as e:
                                pass

                            soup = BeautifulSoup(response.decode('utf-8'),"html.parser")
                            print("Number of url found in Third level: ",len(soup.find_all('a', href=True)))
                            req = 0
                            urls = list(set(soup.find_all('a', href=True)))
                            for link in urls:
                                print("depth?")
                                if waiting_time:
                                    time.sleep(waiting_time)                                
                                if url in str(link["href"]) and req <= max_request and '.mp3' not in str(link["href"]): 
                                    req = req + 1 
                                    link = link["href"] 
                                    print("Crawling now(Third Level): ",link)
                                    async with session.get(link, headers=headers) as response:
                                        response = await response.read()
                                        obj = re.search(r'%s'%regex,response.decode('utf-8'))
                                        result = {}
                                        try:
                                            result["Domain Name"] = url
                                            if obj.group():
                                                return 
                                        except Exception as e:
                                            pass


def remove_fragment(url):
    pure_url, frag = urldefrag(url)
    return pure_url

loop = asyncio.get_event_loop()
count = 0

for url in url_list:
    count += 1
    print ("count:",count)
    try:
        loop.run_until_complete(fetc_data(url, regex_input))
    except Exception as e:
        print("Errror", e)
        pass


file.close()