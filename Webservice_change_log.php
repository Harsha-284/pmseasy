Date: 30/06/2025

The webservices.php code has been updated to take care of banqueteasy bookings hence older code is deprecated and has been renamed as webservices_old_vcet_sarvesh.php and has been kept on the server.


1. Webservices has a new else if block which will be called when the actions === 'book_banqueteasy' to take care of those bookings

2. There is a new column added to the bookings table "banquet_booking_id" which would store the initial bookingid of the splits of the ookings that are processed by the webservice.

EXAMPLE OF THE NEW CHANGE ACCOMODATED FOR BANQUETEASY:

Endpoint (POST): 
https://pmseasy.in/pms/webservice.php


Body:

{
    "post_action": "booking",
    "action": "book_banqueteasy",
    "hotelCode": "empire-royale-hotel",
    "channel": "banqueteasy",
    "bookingId": "81360",
    "cmBookingId": "AAABBBCCC",
    "bookedOn": "2025-06-26 18:35:14",
    "checkin": "2025-07-28",
    "checkout": "2025-07-22",
    "segment": "Banqueteasy",
    "specialRequests": "Airport",
    "pah": false,
    "payment_type": "online",
    "date_of_payment": "2025-02-27",
    "txnid": "2343424",
    "discount_type": "",
    "flat_discount": "",
    "percent_discount": "",
    "cheque_bank": "",
    "cheque_no": "",
    "cheque_date": "",
    "comment": "Payment done",
    "amount_inp": "727",
    "amount": {
        "amountAfterTax": "727",
        "amountBeforeTax": "727",
        "tax": 0,
        "currency": "INR"
    },
    "guest": {
        "firstName": "Hrushikesh",
        "lastName": "Vartak",
        "email": "hrushikesh@aspiringwebsolutions.com",
        "phone": "8605147485",
        "address": {
            "line1": "Naigaon(W)",
            "city": "",
            "state": "Maharashtra",
            "country": "India",
            "zipCode": ""
        }
    },
    "rooms": [
        {
            "roomCode": "non-ac-dorm-bed",
            "rateplanCode": "non-ac-dorm-bed-s-ep",
            "guestName": "Hrushikesh Vartak",
            "occupancy": {
                "adults": 1,
                "children": 0
            },
            "prices": [
                {
                    "checkin": "2025-7-28",
                    "checkout": "2025-7-29"
                }
            ]
        }
    ]
}



