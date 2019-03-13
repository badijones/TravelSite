<?php



include "/inc/_globalIncludeV2.php";


function in_array_r($needle, $haystack, $strict = false) {
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
            return true;
        }
    }

    return false;
}



function hotelsitemap(){


$xmlHead ="<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";

$xmlTail .="\n</urlset>";

$cityNameMissp[] = array("alberta/st-paul","alberta/saint-paul","saint-paul","az");
$cityNameMissp[] = array("arizona/st-johns","arizona/saint-johns","saint-johns","az");
$cityNameMissp[] = array("arizona/st-michaels","arizona/saint-michaels","saint-michaels","az");
$cityNameMissp[] = array("british-columbia/fort-st-john","british-columbia/fort-saint-john","fort-saint-john","bc");
$cityNameMissp[] = array("california/mt-shasta","california/mount-shasta","mount-shasta","ca");
$cityNameMissp[] = array("california/st-helena","california/saint-helena","saint-helena","ca");
$cityNameMissp[] = array("florida/mt-dora","florida/mount-dora","mount-dora","fl");
$cityNameMissp[] = array("illinois/mt-carmel","illinois/mount-carmel","mount-carmel","il");
$cityNameMissp[] = array("illinois/mt-prospect","illinois/mount-prospect","mount-prospect","il");
$cityNameMissp[] = array("illinois/mt-vernon","illinois/mount-vernon","mount-vernon","il");
$cityNameMissp[] = array("illinois/st-charles","illinois/saint-charles","saint-charles","il");
$cityNameMissp[] = array("iowa/mt-pleasant","iowa/mount-pleasant","mount-pleasant","ia");
$cityNameMissp[] = array("iowa/mt-vernon","iowa/mount-vernon","mount-vernon","ia");
$cityNameMissp[] = array("kentucky/mt-sterling","kentucky/mount-sterling","mount-sterling","ky");
$cityNameMissp[] = array("maryland/st-michaels","maryland/saint-michaels","saint-michaels","md");
$cityNameMissp[] = array("michigan/mt-clemens","michigan/mount-clemens","mount-clemens","md");
$cityNameMissp[] = array("michigan/mt-pleasant","michigan/mount-pleasant","mount-pleasant","mi");
$cityNameMissp[] = array("missouri/mt-vernon","missouri/mount-vernon","mount-vernon","mo");
$cityNameMissp[] = array("missouri/st-ann","missouri/saint-ann","saint-ann","mo");
$cityNameMissp[] = array("missouri/st-charles","missouri/saint-charles","saint-charles","mo");
$cityNameMissp[] = array("missouri/st-clair","missouri/saint-clair","saint-clair","mo");
$cityNameMissp[] = array("missouri/st-james","missouri/saint-james","saint-james","mo");
$cityNameMissp[] = array("missouri/st-joseph","missouri/saint-joseph","saint-joseph","mo");
$cityNameMissp[] = array("missouri/st-louis","missouri/saint-louis","saint-louis","mo");
$cityNameMissp[] = array("missouri/st-peters","missouri/saint-peters","saint-peters","mo");
$cityNameMissp[] = array("missouri/st-robert","missouri/saint-robert","saint-robert","mo");
$cityNameMissp[] = array("missouri/ste-genevieve","missouri/sainte-genevieve","sainte-genevieve","mo");
$cityNameMissp[] = array("montana/st-regis","montana/saint-regis","saint-regis","mt");
$cityNameMissp[] = array("new-brunswick/st-andrews","new-brunswick/saint-andrews","saint-andrews","nb");
$cityNameMissp[] = array("new-brunswick/st-jacques","new-brunswick/saint-jacques","saint-jacques","nb");
$cityNameMissp[] = array("new-brunswick/st-john","new-brunswick/saint-john","saint-john","nb");
$cityNameMissp[] = array("new-hampshire/mt-sunapee","new-hampshire/mount-sunapee","mount-sunapee","nh");
$cityNameMissp[] = array("new-jersey/mt-arlington","new-jersey/mount-arlington","mount-arlington","nj");
$cityNameMissp[] = array("new-jersey/mt-ephraim","new-jersey/mount-ephraim","mount-ephraim","nj");
$cityNameMissp[] = array("new-jersey/mt-holly","new-jersey/mount-holly","mount-holly","nj");
$cityNameMissp[] = array("new-jersey/mt-laurel","new-jersey/mount-laurel","mount-laurel","nj");
$cityNameMissp[] = array("new-jersey/mt-olive","new-jersey/mount-olive","mount-olive","nj");
$cityNameMissp[] = array("new-york/mt-kisco","new-york/mount-kisco","mount-kisco","ny");
$cityNameMissp[] = array("new-york/mt-morris","new-york/mount-morris","mount-morris","ny");
$cityNameMissp[] = array("new-york/mt-tremper","new-york/mount-tremper","mount-tremper","ny");
$cityNameMissp[] = array("newfoundland/st-johns","newfoundland/saint-johns","saint-johns","nf");
$cityNameMissp[] = array("north-carolina/mt-airy","north-carolina/mount-airy","mount-airy","nc");
$cityNameMissp[] = array("north-carolina/mt-olive","north-carolina/mount-olive","mount-olive","nc");
$cityNameMissp[] = array("ohio/mt-gilead","ohio/mount-gilead","mount-gilead","oh");
$cityNameMissp[] = array("ohio/mt-orab","ohio/mount-orab","mount-orab","oh");
$cityNameMissp[] = array("ohio/mt-sterling","ohio/mount-sterling","mount-sterling","oh");
$cityNameMissp[] = array("ohio/mt-vernon","ohio/mount-vernon","mount-vernon","oh");
$cityNameMissp[] = array("ohio/st-clairsville","ohio/saint-clairsville","saint-clairsville","oh");
$cityNameMissp[] = array("ohio/st-marys","ohio/saint-marys","saint-marys","oh");
$cityNameMissp[] = array("ontario/mt-hope","ontario/mount-hope","mount-hope","on");
$cityNameMissp[] = array("ontario/sault-ste-marie","ontario/sault-sainte-marie","sault-sainte-marie","on");
$cityNameMissp[] = array("ontario/st-catharines","ontario/saint-catharines","saint-catharines","on");
$cityNameMissp[] = array("ontario/st-marys","ontario/saint-marys","saint-marys","on");
$cityNameMissp[] = array("ontario/st-thomas","ontario/saint-thomas","saint-thomas","on");
$cityNameMissp[] = array("oregon/st-helens","oregon/saint-helens","saint-helens","or");
$cityNameMissp[] = array("pennsylvania/mt-joy","pennsylvania/mount-joy","mount-joy","pa");
$cityNameMissp[] = array("pennsylvania/mt-pocono","pennsylvania/mount-pocono","mount-pocono","pa");
$cityNameMissp[] = array("quebec/st-alexis-des-monts","quebec/saint-alexis-des-monts","saint-alexis-des-monts","pq");
$cityNameMissp[] = array("quebec/st-antoine-de-tilly","quebec/saint-antoine-de-tilly","saint-antoine-de-tilly","pq");
$cityNameMissp[] = array("quebec/st-apollinaire","quebec/saint-apollinaire","saint-apollinaire","pq");
$cityNameMissp[] = array("quebec/st-bernard-de-lacolle","quebec/saint-bernard-de-lacolle","saint-bernard-de-lacolle","pq");
$cityNameMissp[] = array("quebec/st-georges-de-beauce","quebec/saint-georges-de-beauce","saint-georges-de-beauce","pq");
$cityNameMissp[] = array("quebec/st-hyacinthe","quebec/saint-hyacinthe","saint-hyacinthe","pq");
$cityNameMissp[] = array("quebec/st-jean-sur-richelieu","quebec/saint-jean-sur-richelieu","saint-jean-sur-richelieu","pq");
$cityNameMissp[] = array("quebec/st-liboire","quebec/saint-liboire","saint-liboire","pq");
$cityNameMissp[] = array("quebec/st-nicolas","quebec/saint-nicolas","saint-nicolas","pq");
$cityNameMissp[] = array("quebec/st-sauveur","quebec/saint-sauveur","saint-sauveur","pq");
$cityNameMissp[] = array("quebec/ste-adele","quebec/sainte-adele","sainte-adele","pq");
$cityNameMissp[] = array("quebec/ste-agathe-des-monts","quebec/sainte-agathe-des-monts","sainte-agathe-des-monts","pq");
$cityNameMissp[] = array("quebec/ste-foy","quebec/sainte-foy","sainte-foy","pq");
$cityNameMissp[] = array("quebec/ste-marthe","quebec/sainte-marthe","sainte-marthe","pq");
$cityNameMissp[] = array("quebec/ste-helene-de-bagot","quebec/sainte-helene-de-bagot","sainte-helene-de-bagot","pq");
$cityNameMissp[] = array("south-carolina/mt-pleasant","south-carolina/mount-pleasant","mount-pleasant","sc");
$cityNameMissp[] = array("texas/mt-pleasant","texas/mount-pleasant","mount-pleasant","tx");
$cityNameMissp[] = array("texas/mt-vernon","texas/mount-vernon","mount-vernon","tx");
$cityNameMissp[] = array("utah/mt-carmel-junction","utah/mount-carmel-junction","mount-carmel-junction","ut");
$cityNameMissp[] = array("vermont/st-albans","vermont/saint-albans","saint-albans","vt");
$cityNameMissp[] = array("vermont/st-johnsbury","vermont/saint-johnsbury","saint-johnsbury","vt");
$cityNameMissp[] = array("virginia/mt-jackson","virginia/mount-jackson","mount-jackson","va");
$cityNameMissp[] = array("washington/mt-vernon","washington/mount-vernon","mount-vernon","wa");
$cityNameMissp[] = array("west-virginia/mt-hope","west-virginia/mount-hope","mount-hope","wa");
$cityNameMissp[] = array("wisconsin/mt-horeb","wisconsin/mount-horeb","mount-horeb","wi");
$cityNameMissp[] = array("hawaii/honolulu","hawaii/honolulu-oahu","honolulu-oahu","hi");
$cityNameMissp[] = array("new-york/new-york","new-york/new-york-city-nyc","new-york-city-nyc","ny");
$cityNameMissp[] = array("washington-dc/washington","washington-dc/","dc","na");


$cityNameMissp[] = array("hawaii/kailua-kona","hawaii/kailua-kona-hawaii","kailua-kona-hawaii","hi");
$cityNameMissp[] = array("hawaii/wailea","hawaii/wailea-maui","wailea-maui","hi");
$cityNameMissp[] = array("hawaii/poipu","hawaii/poipu-kauai","poipu-kauai","hi");
$cityNameMissp[] = array("hawaii/kaanapali","hawaii/kaanapali-maui","kaanapali-maui","hi");
$cityNameMissp[] = array("hawaii/kihei","hawaii/kihei-maui","kihei-maui","hi");
$cityNameMissp[] = array("hawaii/lahaina","hawaii/lahaina-maui","lahaina-maui","hi");
$cityNameMissp[] = array("hawaii/waikoloa","hawaii/kohala-coast-hawaii","kohala-coast-hawaii","hi");
$cityNameMissp[] = array("florida/sunny-isles-beach","florida/sunny-isles","sunny-isles","fl");
$cityNameMissp[] = array("hawaii/kohala-coast","hawaii/kohala-coast-hawaii","kohala-coast-hawaii","hi");
$cityNameMissp[] = array("hawaii/lihue","hawaii/lihue-kauai","lihue-kauai","hi");
$cityNameMissp[] = array("hawaii/princeville","hawaii/princeville-kauai","princeville-kauai","hi");
$cityNameMissp[] = array("florida/hallandale-beach","florida/hallandale","hallandale","fl");
$cityNameMissp[] = array("massachusetts/dennis-port","massachusetts/dennisport","dennisport","ma");
$cityNameMissp[] = array("quebec/quebec","quebec/quebec-city","quebec-city","pq");
$cityNameMissp[] = array("hawaii/hilo","hawaii/hilo-hawaii","hilo-hawaii","hi");
$cityNameMissp[] = array("hawaii/honokowai","hawaii/honokowai-maui","honokowai-maui","hi");
$cityNameMissp[] = array("hawaii/kahana","hawaii/kahana-maui","kahana-maui","hi");
$cityNameMissp[] = array("hawaii/kahului","hawaii/kahului-maui","kahului-maui","hi");
$cityNameMissp[] = array("hawaii/kapalua","hawaii/kapalua-maui","kapalua-maui","hi");
$cityNameMissp[] = array("hawaii/napili","hawaii/napili-maui","napili-maui","hi");
$cityNameMissp[] = array("hawaii/waimea","hawaii/waimea-hawaii","waimea-hawaii","hi");
$cityNameMissp[] = array("hawaii/kaunakakai","hawaii/kaunakakai-molokai","kaunakakai-molokai","hi");
$cityNameMissp[] = array("hawaii/lanai-city","hawaii/lanai-city-lanai","lanai-city-lanai","hi");
$cityNameMissp[] = array("wisconsin/deforest","wisconsin/de-forest","de-forest","wi");
$cityNameMissp[] = array("hawaii/ko-olina","hawaii/koolina-oahu","koolina-oahu","hi");
$cityNameMissp[] = array("hawaii/makawao","hawaii/makawao-maui","makawao-maui","hi");
$cityNameMissp[] = array("hawaii/kaneohe","hawaii/kaneohe-oahu","kaneohe-oahu","hi");
$cityNameMissp[] = array("hawaii/laie","hawaii/laie-oahu","laie-oahu","hi");
$cityNameMissp[] = array("hawaii/turtle-bay","hawaii/turtle-bay-oahu","turtle-bay-oahu","hi");
$cityNameMissp[] = array("arizona/pinetop","arizona/pinetop-lakeside","pinetop-lakeside","az");
$cityNameMissp[] = array("florida/saint-petersburg","florida/st-petersburg","st-petersburg","fl");
$cityNameMissp[] = array("hawaii/hana","hawaii/hana-maui","hana-maui","hi");
$cityNameMissp[] = array("hawaii/honomu","hawaii/honomu-hawaii","honomu-hawaii","hi");
$cityNameMissp[] = array("hawaii/paia","hawaii/paia-maui","paia-maui","hi");
$cityNameMissp[] = array("ontario/kanata","ontario/kanata-ottawa","kanata-ottawa","on");
$cityNameMissp[] = array("rhode-island/bristol","rhode-island/bristol-harbor","bristol-harbor","ri");
$cityNameMissp[] = array("virginia/chincoteague","virginia/chincoteague-island","chincoteague-island","va");
$cityNameMissp[] = array("hawaii/hanalei","hawaii/hanalei-kauai","hanalei-kauai","hi");
$cityNameMissp[] = array("hawaii/pahala","hawaii/pahala-hawaii","pahala-hawaii","hi");
/*
https://hotelguides.com/ohio/kings-island-oh-hotels.html	https://hotelguides.com/ohio/kings-island-park-hotels.html
https://hotelguides.com/guam/guam-gu-hotels.html	https://hotelguides.com/guam/gu-hotels.html
https://hotelguides.com/us-virgin-islands/vi-hotels.html	https://hotelguides.com/us-virgin-islands/usvi-hotels.html
https://hotelguides.com/us-virgin-islands/st-thomas-vi-hotels.html	https://hotelguides.com/us-virgin-islands/saint-thomas-hotels.html
https://hotelguides.com/hawaii/kapaa-hi-hotels.html	https://hotelguides.com/hawaii/kauai-hi-kapaa-hotels.html
https://hotelguides.com/us-virgin-islands/st-croix-vi-hotels.html	https://hotelguides.com/us-virgin-islands/saint-croix-hotels.html
https://hotelguides.com/us-virgin-islands/st-john-vi-hotels.html	https://hotelguides.com/us-virgin-islands/st-john-usvi-hotels.html
*/
$cityNameMissp[] = array("ohio/kings-island","ohio/kings-island-park","kings-island-park","na");
$cityNameMissp[] = array("guam/guam","guam/gu","gu","na");
$cityNameMissp[] = array("guam/guam","guam/gu","gu","na");
//https://hotelguides.com/us-virgin-islands/vi-hotels.html	https://hotelguides.com/us-virgin-islands/usvi-hotels.html
$cityNameMissp[] = array("us-virgin-islands/st-thomas","us-virgin-islands/saint-thomas","saint-thomas","usvi~");
$cityNameMissp[] = array("hawaii/kapaa","hawaii/kauai-hi-kapaa","kauai-hi-kapaa","na");
$cityNameMissp[] = array("us-virgin-islands/st-croix","us-virgin-islands/saint-croix","saint-croix","usvi~");




$attr_abmisp['AL'] = array("Alabama","alabama");
$attr_abmisp['AK'] = array("Alaska","alaska");
$attr_abmisp['AZ'] = array("Arizona","arizona");
$attr_abmisp['AR'] = array("Arkansas","arkansas");
$attr_abmisp['CA'] = array("California","california");
$attr_abmisp['CO'] = array("Colorado","colorado");
$attr_abmisp['CT'] = array("Connecticut","connecticut");
$attr_abmisp['DE'] = array("Delaware","delaware");
$attr_abmisp['DC'] = array("District of Columbia","washington-dc");
$attr_abmisp['FL'] = array("Florida","florida");
$attr_abmisp['GA'] = array("Georgia","georgia");
$attr_abmisp['HI'] = array("Hawaii","hawaii");
$attr_abmisp['ID'] = array("Idaho","idaho");
$attr_abmisp['IL'] = array("Illinois","illinois");
$attr_abmisp['IN'] = array("Indiana","indiana");
$attr_abmisp['IA'] = array("Iowa","iowa");
$attr_abmisp['KS'] = array("Kansas","kansas");
$attr_abmisp['KY'] = array("Kentucky","kentucky");
$attr_abmisp['LA'] = array("Louisiana","louisiana");
$attr_abmisp['ME'] = array("Maine","maine");
$attr_abmisp['MD'] = array("Maryland","maryland");
$attr_abmisp['MA'] = array("Massachusetts","massachusetts");
$attr_abmisp['MI'] = array("Michigan","michigan");
$attr_abmisp['MN'] = array("Minnesota","minnesota");
$attr_abmisp['MS'] = array("Mississippi","mississippi");
$attr_abmisp['MO'] = array("Missouri","missouri");
$attr_abmisp['MT'] = array("Montana","montana");
$attr_abmisp['NE'] = array("Nebraska","nebraska");
$attr_abmisp['NV'] = array("Nevada","nevada");
$attr_abmisp['NH'] = array("New Hampshire","new-hampshire");
$attr_abmisp['NJ'] = array("New Jersey","new-jersey");
$attr_abmisp['NM'] = array("New Mexico","new-mexico");
$attr_abmisp['NY'] = array("New York","new-york");
$attr_abmisp['NC'] = array("North Carolina","north-carolina");
$attr_abmisp['ND'] = array("North Dakota","north-dakota");
$attr_abmisp['OH'] = array("Ohio","ohio");
$attr_abmisp['OK'] = array("Oklahoma","oklahoma");
$attr_abmisp['OR'] = array("Oregon","oregon");
$attr_abmisp['PA'] = array("Pennsylvania","pennsylvania");
$attr_abmisp['PR'] = array("Puerto Rico","puerto-rico");
$attr_abmisp['RI'] = array("Rhode Island","rhode-island");
$attr_abmisp['SC'] = array("South Carolina","south-carolina");
$attr_abmisp['SD'] = array("South Dakota","south-dakota");
$attr_abmisp['TN'] = array("Tennessee","tennessee");
$attr_abmisp['TX'] = array("Texas","texas");
$attr_abmisp['UT'] = array("Utah","utah");
$attr_abmisp['VT'] = array("Vermont","vermont");
$attr_abmisp['VA'] = array("Virginia","virginia");
$attr_abmisp['WA'] = array("Washington","washington");
$attr_abmisp['WV'] = array("West Virginia","west-virginia");
$attr_abmisp['WI'] = array("Wisconsin","wisconsin");
$attr_abmisp['WY'] = array("Wyoming","wyoming");


$attr_abmisp['VI'] = array("US Virgin Islands","us-virgin-islands");
$attr_abmisp['GU'] = array("Guam","guam");
$attr_abmisp['CY'] = array("Cayman Islands","cayman-islands");


$attr_abmisp['AB'] = array("Alberta","alberta");
$attr_abmisp['BC'] = array("British Columbia","british-columbia");
$attr_abmisp['MB'] = array("Manitoba","manitoba");
$attr_abmisp['NB'] = array("New Brunswick","new-brunswick");
$attr_abmisp['NF'] = array("Newfoundland","newfoundland");
$attr_abmisp['NS'] = array("Nova Scotia","nova-scotia");
$attr_abmisp['NU'] = array("Nunavut","nunavut");
$attr_abmisp['NT'] = array("Northwest Territories","northwest-territories");
$attr_abmisp['ON'] = array("Ontario","ontario");
$attr_abmisp['PE'] = array("Prince Edward Island","prince-edward-island");
$attr_abmisp['PQ'] = array("Quebec","quebec");
$attr_abmisp['QC'] = array("Quebec","quebec");
$attr_abmisp['SK'] = array("Saskatchewan","saskatchewan");
$attr_abmisp['YT'] = array("Yukon","yukon");
$attr_abmisp['YK'] = array("Yukon","yukon");








if($attr_conn = mysql_connect($attr_dbhost,$attr_dbuser,$attr_dbpass)) {
mysql_select_db($attr_dbname);



// NEARBY HOTELS - TAKES: $attr_nextlatitude, $attr_nextlongitude
$newsqlhotels = "SELECT * FROM hg_hotels_dynamic WHERE hg_id !=0 AND tp_id != '' AND commented = '0'  "; 







#----------------------------HOTEL----------------


//echo "<!-- $attr_citysql -->";

$hotel_cityresult = mysql_query($newsqlhotels) or die (mysql_error()."<br />Couldn't execute query: $hotel_query");
$hotel_citynrows = mysql_num_rows($hotel_cityresult);

if ($hotel_citynrows >0){


for ($hotel_i = 0; $hotel_i < $hotel_citynrows; $hotel_i++){

$hotel_row = mysql_fetch_array($hotel_cityresult);





$hotelname = $hotel_row["name"];
$hotelid = $hotel_row["hg_id"];
$distance = round($hotel_row["distance"], 1);
$sidenavfilename = $hotel_row["hg_id"];
$sidenavcityurl = strtolower(preg_replace('#[ ,.]+#', '-', $hotel_row['city']));
$sidenavcityurl = str_replace("'","",$sidenavcityurl);

if($hotel_row['country']=="KY"){
$sidenavbigstate = $attr_abmisp['CY'][0];
}else{
$sidenavbigstate = $attr_abmisp[$hotel_row['state']][0];
}


$sidenavurlstate = strtolower(preg_replace('#[ ,.]+#', '-', $sidenavbigstate));
$sidenavurlstate = str_replace("'","",$sidenavurlstate);

if($sidenavurlstate == 'virgin-islands') $sidenavurlstate = 'us-virgin-islands';
$snfilename = "https://hotelguides.com/hotels/$sidenavurlstate/$sidenavcityurl/$sidenavfilename.html";




$hotelXML .="   <url>\n      <loc>$snfilename</loc>\n      <changefreq>weekly</changefreq>\n      <priority>0.6</priority>\n   </url>\n";











}




}





mysql_close($attr_conn);



}






/* END ATTR INCLUDE */

$file = fopen("/httpdocs/zsitemap_hotels.xml","w");
fwrite($file,$xmlHead.$hotelXML.$xmlTail);
fclose($file);

}





$addToSitemap = <<< FOOLF
   <url>
      <loc>https://hotelguides.com/</loc>
      <changefreq>daily</changefreq>
      <priority>0.5</priority>
   </url>
   <url>
      <loc>https://hotelguides.com/address-search.php</loc>
      <changefreq>daily</changefreq>
      <priority>0.5</priority>
   </url>
   <url>
      <loc>https://hotelguides.com/name-search.php</loc>
      <changefreq>daily</changefreq>
      <priority>0.5</priority>
   </url>
   <url>
      <loc>https://hotelguides.com/hotels-near-me.html</loc>
      <changefreq>daily</changefreq>
      <priority>0.5</priority>
   </url>
FOOLF;

$sitemapCats['105'] = 'attr1';
$sitemapCats['110'] = 'attr1';
$sitemapCats['115'] = 'attr1';
$sitemapCats['130'] = 'attr1';
$sitemapCats['201'] = 'attr1';
$sitemapCats['205'] = 'attr1';
$sitemapCats['206'] = 'attr1';
$sitemapCats['207'] = 'attr1';
$sitemapCats['210'] = 'attr1';
$sitemapCats['211'] = 'attr1';
$sitemapCats['217'] = 'attr1';
$sitemapCats['219'] = 'attr1';
$sitemapCats['220'] = 'attr1';
$sitemapCats['224'] = 'attr1';
$sitemapCats['225'] = 'attr1';
$sitemapCats['230'] = 'attr1';
$sitemapCats['231'] = 'attr1';
$sitemapCats['235'] = 'attr1';
$sitemapCats['236'] = 'attr1';
$sitemapCats['237'] = 'attr1';
$sitemapCats['240'] = 'attr1';
$sitemapCats['245'] = 'attr1';
$sitemapCats['255'] = 'attr1';
$sitemapCats['259'] = 'attr1';
$sitemapCats['260'] = 'attr1';
$sitemapCats['265'] = 'attr1';
$sitemapCats['295'] = 'attr1';
$sitemapCats['315'] = 'attr1';
$sitemapCats['317'] = 'attr1';
$sitemapCats['318'] = 'attr1';
$sitemapCats['319'] = 'attr1';
$sitemapCats['320'] = 'attr1';




$sitemapCats['106'] = 'attr2';
$sitemapCats['116'] = 'attr2';
$sitemapCats['215'] = 'attr2';
$sitemapCats['221'] = 'attr2';
$sitemapCats['228'] = 'attr2';
$sitemapCats['52'] = 'attr2';
$sitemapCats['58'] = 'attr2';
$sitemapCats['59'] = 'attr2';
$sitemapCats['114'] = 'attr2';
$sitemapCats['125'] = 'attr2';
$sitemapCats['131'] = 'attr2';
$sitemapCats['135'] = 'attr2';
$sitemapCats['232'] = 'attr2';
$sitemapCats['249'] = 'attr2';
$sitemapCats['250'] = 'attr2';
$sitemapCats['253'] = 'attr2';
$sitemapCats['262'] = 'attr2';
$sitemapCats['501'] = 'attr2';
$sitemapCats['551'] = 'attr2';
$sitemapCats['553'] = 'attr2';
$sitemapCats['555'] = 'attr2';
$sitemapCats['559'] = 'attr2';
$sitemapCats['571'] = 'attr2';
$sitemapCats['595'] = 'attr2';
$sitemapCats['701'] = 'attr2';
$sitemapCats['702'] = 'attr2';
$sitemapCats['703'] = 'attr2';
$sitemapCats['799'] = 'attr2';


$sitemapCats['304'] = 'city1';
$sitemapCats['305'] = 'city1';
$sitemapCats['15'] = 'city1';
$sitemapCats['16'] = 'city1';
$sitemapCats['17'] = 'city1';


$sitemapCats['308'] = 'city3';
$sitemapCats['20'] = 'city3';


$sitemapCats['306'] = 'city2';
$sitemapCats['307'] = 'city2';
$sitemapCats['18'] = 'city2';
$sitemapCats['19'] = 'city2';


$sitemapCats['119'] = 'colleges';
$sitemapCats['120'] = 'colleges';
$sitemapCats['121'] = 'colleges';
$sitemapCats['122'] = 'colleges';
$sitemapCats['123'] = 'colleges';
$sitemapCats['124'] = 'colleges';



$sitemapCats['310'] = 'dist';
$sitemapCats['10'] = 'dist';
$sitemapCats['11'] = 'dist';
$sitemapCats['12'] = 'dist';


$sitemapCats['70'] = 'pets';


$xmlHead ="<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";



if($dbconn = mysql_connect($dbdbhost,$dbdbuser,$dbdbpass)) {

mysql_select_db($dbdbname);






$xmlsql = "SELECT url,popularity,meta_robots,zas from hg_pages where sponsored_ids = '' AND meta_robots ='index,follow' ";



$xmlresult = mysql_query($xmlsql) or die (mysql_error()."<br />Couldn't execute query: $xmlquery");
$xmlnrows = mysql_num_rows($xmlresult);

if ($xmlnrows >0){


for ($i = 0; $i < $xmlnrows; $i++){

$xmlrow = mysql_fetch_array($xmlresult);

$xmlUrl = $xmlrow["url"];
$pop = intval( $xmlrow["popularity"]);
$meta_robots = $xmlrow["meta_robots"];

$freq = 'monthly';

if($pop > 0){
$freq = 'daily';
}

if($pop ==='0'){
$priority = '0.2';
}else if($pop > 100){
$priority = '0.5';
}else if($pop > 50){
$priority = '0.6';
}else if($pop > 15){
$priority = '0.7';
}else if($pop > 5){
$priority = '0.8';
}else if($pop > 0){
$priority = '1.0';
}

$priority = '0.5';


if($meta_robots === 'noindex,follow')
$priority = '0.1';

if(!stristr($xmlUrl, '&')  && $xmlUrl != 'https://hotelguides.com/index.html' && (  ! stristr($xmlUrl, '/answer-tree')   ) && (  ! stristr($xmlUrl, '/help/')   )){







if (array_key_exists($xmlrow["zas"], $sitemapCats))
$xmlOutput[$sitemapCats[$xmlrow["zas"]]] .="   <url>\n      <loc>$xmlUrl</loc>\n      <changefreq>$freq</changefreq>\n      <priority>$priority</priority>\n   </url>\n";
else
$xmlOutput['other'] .="   <url>\n      <loc>$xmlUrl</loc>\n      <changefreq>$freq</changefreq>\n      <priority>$priority</priority>\n   </url>\n";




}


}


}


}


$xmlTail .="\n</urlset> ";


$mainHead = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";

$mainTail = "\n</sitemapindex>";


foreach($xmlOutput as $catKey => $catVal){

if($catKey === 'other')
$xmlOutput[$catKey] .= $addToSitemap;

echo "\n~$catKey:".substr_count($xmlOutput[$catKey], '<url>')."~\n";




$file = fopen("/var/www/vhosts/hotelguides.com/httpdocs/zsitemap_".$catKey.".xml","w");
fwrite($file,$xmlHead.$xmlOutput[$catKey].$xmlTail);
fclose($file);


$mainSitemap .= "
	<sitemap>
		<loc>https://hotelguides.com/zsitemap_".$catKey.".xml</loc>
		<lastmod>".date('c')."</lastmod>
	</sitemap>";

}


$mainSitemap .= "
	<sitemap>
		<loc>https://hotelguides.com/zsitemap_hotels.xml</loc>
		<lastmod>".date('c')."</lastmod>
	</sitemap>";


$file = fopen("/var/www/vhosts/hotelguides.com/httpdocs/sitemap.xml","w");
fwrite($file,$mainHead.$mainSitemap.$mainTail);
fclose($file);


hotelsitemap();



echo 'done';

?>