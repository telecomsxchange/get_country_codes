# Get Country Codes, Names via TelecomsXChange API

![Alt text](https://s3.amazonaws.com/cdn.freshdesk.com/data/helpdesk/attachments/production/11022455717/original/EGdsXGq8fSRQpIXZISV6ML0DvbkaeduXXg.png?1506205725 "Autocomplete Get Countries list via API by Prefix search")



This TelecomsXChange API allows you to get an updated complete list of all country codes + area codes + country name and network operator name via a single API call from TelecomsXChange backend.

The best way to demonstrate how this works is via an open source example that you can use as a base to build on top of it.

The open-source app we are publishing will have a search input and once you start typing the country code it will show a complete list of all prefixes for this country along with country and network operator names, then when you search it will utilize the rate lookup api to get high/low rates from the market for this specific country code selected by user.

Sample page where this code is utilized (www.telecomsxchange.com/rates)

There will be two files in this example: index.php and get_zoom_list.php 

<h2>What you need to have to use this source code: </h2>

1- TelecomsXChange Buyer Account ( Sign up for one here: www.telecomsxchange.com/voice_api 

2- API Key (Generated from inside the portal after you signup)


