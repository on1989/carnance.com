#JWA Car Listing

Adds to the topic the ability to expose the car to order. Listing Vehicle with filtering Integration with WPBakery


#### REST API
Before start using REST API

* Login in Admin panel 
* Activation plugin
* Go to plugin settings and generate a token key

##### Examples and Breakpoints

**Crete Car**

`POST https://exampl.com/wp-json/jwa-cars-listing/v1/cars/
Content-Type: application/json`

`{
   "token": "generate in admin pannel",
   "title": "BMW",
   "carStatus": "publish",
   "vin": "KM8SNDHF8FU115436",
   "carStockNumber": "323223",
   "cityMpg": "10",
   "highwayMpg": "8",
   "carMark": "BMW",
   "carModel": "M5",
   "carTrim": "3.3 Torbo disel",
   "bodyType": "Sedan",
   "fuelType": "Disel",
   "carYear": "2019",
   "carMileage": 29911,
   "horsePower": "239",
   "carEngine": "3.3 L",
   "transmission": "Manual",
   "interiorColor": "Black",
   "exteriorColor": "White",
   "airbagNum": 3,
   "seatingNum": 4,
   "doorsNum": 4,
   "price": [
     {
       "regular": 34002
     },
     {
       "sales": 32880
     }
   ],
   "gallery": [
     "https://example.com/uploads/pic-47ee9ec7c4b6673d3c01ca0a8192ac9e-e1604924770973-798x466.jpg"
     "https://example.com/uploads/pic-47ee9ec7c4b6673d3c01ca0a8192ac9e-e1604924770973-798x466.jpg"
     "https://example.com/uploads/pic-47ee9ec7c4b6673d3c01ca0a8192ac9e-e1604924770973-798x466.jpg"
   ],
   "dealer": [
     {
       "city": "Toronto"
     },
     {
       "postal_code": "NO 638"
     },
     {
       "province": "Ontario"
     },
     {
       "address": "111 Buttermill Ave Unit #2, Concord, ON L4K 3X5"
     },
     {
       "dealerName": "Dealear Name"
     }
   ]
 }
`

**Answer**

`{
   "statusCode": 200,
   "car_id": 17533,
   "gallery": {
     [123, 322, 443, 323]
   }
 }`
 
**Get Car ID**

`GET https://exampl.com/wp-json/jwa-cars-listing/v1/cars/19312`

**Answer**

`{
   "carID": 19312,
   "title": "2018 | MINI | Countryman | Cooper S ALL4 | 6SP | LOW MILEAGE 2.0L I4 16V GDI DOHC Turbo",
   "carStatus": "publish",
   "carLink": "https:\/\/exampl.com\/car\/buy-2018-mini-countryman-cooper-s-all4-6sp-low-mileage-2-0l-i4-16v-gdi-dohc-turbo-j3e68961\/",
   "image": "<img width=\"1024\" height=\"768\" src=\"https:\/\/exampl.com\/wp-content\/uploads\/2020\/11\/79304c9efc9bf8aa5d43e48c9f7e7badx-1.jpg\" class=\"attachment-full size-full wp-post-image\" alt=\"\" loading=\"lazy\" srcset=\"https:\/\/thompson.justwebagency.com\/get-auto\/wp-content\/uploads\/2020\/11\/79304c9efc9bf8aa5d43e48c9f7e7badx-1.jpg 1024w, https:\/\/thompson.justwebagency.com\/get-auto\/wp-content\/uploads\/2020\/11\/79304c9efc9bf8aa5d43e48c9f7e7badx-1-300x225.jpg 300w, https:\/\/thompson.justwebagency.com\/get-auto\/wp-content\/uploads\/2020\/11\/79304c9efc9bf8aa5d43e48c9f7e7badx-1-768x576.jpg 768w, https:\/\/thompson.justwebagency.com\/get-auto\/wp-content\/uploads\/2020\/11\/79304c9efc9bf8aa5d43e48c9f7e7badx-1-262x196.jpg 262w, https:\/\/thompson.justwebagency.com\/get-auto\/wp-content\/uploads\/2020\/11\/79304c9efc9bf8aa5d43e48c9f7e7badx-1-384x288.jpg 384w\" sizes=\"(max-width: 1024px) 100vw, 1024px\" \/>",
   "vin": "WMZYT5C34J3E68961",
   "carStockNumber": "P695",
   "cityMpg": "20L\/100Km",
   "highwayMpg": "30L\/100Km",
   "carMark": {
     "name": "MINI",
     "termID": "918"
   },
   "carModel": {
     "name": "Countryman",
     "termID": "919"
   },
   "carTrim": {
     "name": "Cooper S ALL4 | 6SP | LOW MILEAGE 2.0L I4 16V GDI DOHC Turbo",
     "termID": "920"
   },
   "bodyType": "SUV",
   "fuelType": "Gas",
   "carYear": "2018",
   "carMileage": "17318",
   "horsePower": "",
   "carEngine": {
     "name": "2.0L I4 16V GDI DOHC Turbo",
     "termID": "910"
   },
   "transmission": "Manual",
   "interiorColor": "Cloth",
   "exteriorColor": "Light White",
   "airbagNum": "5",
   "seatingNum": "5",
   "doorsNum": "4",
   "price": {
     "regular": "29995",
     "sales": "",
     "biweekly": "294.85"
   },
   "gallery": {
     "ids": "19313,19314,19315",
     "urls": [
       "https:\/\/exampl.com\/uploads\/2020\/11\/13715badb122c6b13e934dc79e1edad2x-1.jpg",
       "https:\/\/exampl.com\/uploads\/2020\/11\/13715badb122c6b13e934dc79e1edad2x-1.jpg",
       "https:\/\/exampl.com\/uploads\/2020\/11\/13715badb122c6b13e934dc79e1edad2x-1.jpg",
       "https:\/\/exampl.com\/uploads\/2020\/11\/13715badb122c6b13e934dc79e1edad2x-1.jpg",
     ]
   },
   "dealer": {
     "city": "",
     "postal_code": "",
     "province": "",
     "address": "",
     "dealerName": ""
   }
 }`
 
 **Get Car by VIN**
 
 `GET https://exampl.com/wp-json/jwa-cars-listing/v1/cars-by-vin/WMZYT5C34J3E68961`
 
**Answer**

`{
   "statusCode": 200,
   "ID": 19312,
   "carStatus": "publish"
 }`
 
 **Update car** 

`POST https://exampl.com/wp-json/jwa-cars-listing/v1/cars/ID_CAR
 Content-Type: application/json`
 
 `{
    "token": "generate in admin pannel",
    "ID": 510, // requere
    "status": "draft" // requere publish | draft | private
 }`


 
 
