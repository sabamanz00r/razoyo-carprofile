Razoyo_CarProfile

Author: Saba Manzoor

Explanation: This module uses the Razoyo API and allow customer to select the car in My Account Section

# CarProfile

Description:

Register module will be mainly used to allow the customer to select the car.

Requirements:

    - User must be logged in to select the car

Key features:

      1. User can select the car
      2. Once car is selected, they can see their selected car or they can change and select a new car from the list.

Module Installation

Install the extension by executing the following command:

`composer require razoyo/module-carprofile`

OR

Download the extension and Unzip the files in `app/code/Razoyo/CarProfile`

Upload it to your Magento installation root directory

Then

Execute the following commands respectively:

1.  `php bin/magento module:enable Razoyo_CarProfile`

2.  `php bin/magento setup:upgrade`

3.  `php bin/magento setup:di:compile`

Refresh the Cache under System ⇾ Cache Management

Navigate to **Stores ⇾ Configuration ⇾ Customers** and the module **Razoyo Cars** under Customers tab.


**Configuration**

1. Navigate to **Stores ⇾ Configuration  ⇾ Customers** and click on **Razoyo Cars** under Customers tab in the left panel.

 ![Configuration](https://i.ibb.co/Fm3Kvnh/Screenshot-from-2024-07-29-05-19-54.png)
 
**Razoyo Cars Configuration**

* Module Enable

This is module main enable/disable button. This will decide either module is enable or disabled

**Razoyo List all cars API Endpoint**

Here you will enter the API endpoint URL

* Integration Access Token

Here you will enter the access token. You can generate the access token here -> System -> Extensions -> Integrations

**Frontend**

After enabling module, setting up the API endpoint and access token.
You can see the My Car Profile in Account Section (You must need to be logged in to view this section).

 ![CarProfile](https://i.ibb.co/mXGHPBy/Screenshot-from-2024-07-29-05-24-31.png)

Here you can see your selected car and below that you can select the car from the list.
You can also filter the car based on Car Make.
You can click on "Change Car" to select any other car.
