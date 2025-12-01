let selectedRooms = new Map();  // Stores all room types
let filteredRooms = new Map();  // Stores only the selected room types

function intialRoomLoad(roomtypeid, roomtypename, cmroomtypename, fulldaytariff, occupancy) {
    let checkinDateBooking = document.querySelector('input[name="checkinDateBooking"]').value;
    let checkoutDateBooking = document.querySelector('input[name="checkoutDateBooking"]').value;
    let old_childBooking = parseInt(document.querySelector('input[name="childBooking"]').value);
    let old_adultBooking = parseInt(document.querySelector('input[name="adultBooking"]').value);
    let no_of_rooms = parseInt(document.querySelector('input[name="noOfRooms"]').value);

    let childBooking = Math.round(old_childBooking / no_of_rooms)
    let adultBooking = Math.round(old_adultBooking / no_of_rooms)


    $.ajax({
        type: 'POST',
        url: 'bookingajax.php',
        data: {
            rt: roomtypeid,
            cidt: checkinDateBooking,
            codt: checkoutDateBooking,
            adults: adultBooking,
            children: childBooking,
            no_of_rooms: no_of_rooms,
            action: 'search_booking'
        },
        success: function (response) {
            let data = JSON.parse(response);
            let anima = (parseInt(data.total_rooms) / parseInt(occupancy)) * 100;

            selectedRooms.set(roomtypeid, { roomtypename, totalRooms: data.total_rooms, cmroomtypename, fulldaytariff, occupancy, anima, roomtypeid, checkinDateBooking, checkoutDateBooking, childBooking, adultBooking, no_of_rooms, extraCharge: parseInt(data.extra_charge), old_adult: old_adultBooking, old_child: old_childBooking });
            document.getElementById(`1stmodalAvailibility-${roomtypeid}`).textContent = data.total_rooms;
            renderCards();
        },
        error: function (err) {
            console.log('Error finding data', err);
        }
    });
}

function search_bookings(roomtypeid, checkbox, roomtypename, cmroomtypename, fulldaytariff, occupancy) {
    let checkinDateBooking = document.querySelector('input[name="checkinDateBooking"]').value;
    let checkoutDateBooking = document.querySelector('input[name="checkoutDateBooking"]').value;
    let old_childBooking = document.querySelector('input[name="childBooking"]').value;
    let old_adultBooking = document.querySelector('input[name="adultBooking"]').value;
    let no_of_rooms = document.querySelector('input[name="noOfRooms"]').value;

    let childBooking = Math.round(old_childBooking / no_of_rooms)
    let adultBooking = Math.round(old_adultBooking / no_of_rooms)

    if (checkbox.checked) {
        $.ajax({
            type: 'POST',
            url: 'bookingajax.php',
            data: {
                rt: roomtypeid,
                cidt: checkinDateBooking,
                codt: checkoutDateBooking,
                adults: adultBooking,
                children: childBooking,
                no_of_rooms: no_of_rooms,
                action: 'search_booking'
            },
            success: function (response) {
                let data = JSON.parse(response);
                let anima = (parseInt(data.total_rooms) / parseInt(occupancy)) * 100;
                filteredRooms.set(roomtypeid, { roomtypename, totalRooms: data.total_rooms, cmroomtypename, fulldaytariff, occupancy, anima, roomtypeid, checkinDateBooking, checkoutDateBooking, childBooking, adultBooking, no_of_rooms, extraCharge: parseInt(data.extra_charge), old_adult: old_adultBooking, old_child: old_childBooking });
                renderCards();
            },
            error: function (err) {
                console.log('Error finding data', err);
            }
        });
    } else {
        filteredRooms.delete(roomtypeid);
        renderCards();
    }
}

function fetchRatePlan(roomtypeid) {
    let currendate = new Date();
    let date = `${currendate.getFullYear()}-0${currendate.getMonth() + 1}-${currendate.getDate()}`;

    return fetch(`bookingajax.php?roomtypeid=${roomtypeid}&date=${date}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        },
    })
        .then(response => response.json())
        .then(data => {
            let str = '';
            let occupancy = data.room_type_info;
            if (data && data.data && Array.isArray(data.data)) {
                data.data.forEach(item => {
                    // ${value.roomtypename.length > 22 ? value.roomtypename.substring(0, 22) + "..." : value.roomtypename}
                    str += `
                        <div style="font-size: 11px; text-align: justify; " >
                            <div class="bullet available" style="display: inline-block; width: 9px;height: 9px;border-radius: 50%;" ></div>
                            <span class="booked-text" style="display: inline" >₹${Number(item.fulldaytariff)}/-</span>
                            <span style="display: inline" >${item.room_rate_plan.length > 23 ? item.room_rate_plan.substring(0, 23) + "..." : item.room_rate_plan}</span>
                        </div>
                `;
                });
            } else {
                console.error('Unexpected data format:', data);
            }
            return { str: str, occupancy: occupancy }; // Return the populated string
        })
        .catch(error => {
            console.error('Error:', error);
        });
}


function renderCards() {
    document.getElementById('cardsContainer').innerHTML = '';

    // Determine which rooms to display: all if no filter is applied, otherwise filteredRooms
    let roomsToDisplay = filteredRooms.size > 0 ? filteredRooms : selectedRooms;

    roomsToDisplay.forEach(async (value, key) => {

        let uniqueClass = `line-animation-${key}`;
        let card = document.createElement('div');
        card.className = 'card';
        // let rateplan = await fetchRatePlan(value.roomtypeid)

        card.dataset.roomdata = JSON.stringify(value);
        let encodedData = encodeURIComponent(card.dataset.roomdata);
        card.innerHTML = `
        <div  class="col-sm-6 autoTextarea " style=" box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;  padding: 8px; width: 262.5px;  height: 150px;   margin: 0px 8px 8px 0px; text-align: center; overflow-y: auto; background-color: ${value.totalRooms > 0 ? 'white' : '#ffe3e3'}">
            <a ${value.totalRooms > 0 ? `href="LBF_userdetails.php?data=${encodedData}"` : ''}  class="card openInfoModalButton" style="text-decoration: none; color: black; cursor: ${value.totalRooms > 0 ? 'pointer' : 'auto'}; ">
                <div class="card-body">
                    <h5 class="card-title" style="margin: 0px 0px; font-size: 14px; text-align: justify; ">
                        ${value.roomtypename.length > 22 ? value.roomtypename.substring(0, 22) + "..." : value.roomtypename}
                    </h5>
                    <div class="available" id="line" style="width: 30px; height: 2px; margin: 5px 0px; " ></div>
                    <p class="card-text available-text " style="font-weight: bold; margin: 0px 0px; font-size: 25px;">${value.totalRooms}</p>
                    <small id="room-occupancy-${value.roomtypeid}">(2 + 1 + 1)</small>

                    <div id="rateplan-${value.roomtypeid}">
                        Loading rate plan...
                    </div>
                    
                    ${value.extraCharge > 0 ? `
                     <div style="font-size: 11px; text-align: justify; " >
                            <div class="bullet available" style="display: inline-block; width: 9px;height: 9px;border-radius: 50%;" ></div>
                            <span class="booked-text" style="display: inline" >₹${value.extraCharge}/-</span>
                            <span style="display: inline" >Extra Charge Per Person</span>
                     </div>`: ''
            }
                </div>
            </a>
        </div>
        
        `;

        // Create unique style for the animation
        let style = document.createElement('style');
        style.innerHTML = `
        @keyframes ${uniqueClass} {
            from {
                width: 0%;
            }
            to {
                width: ${value.anima}%;
            }
        }
        .${uniqueClass} {
            top: 0;
            width: 0;
            height: 3px;
            background-color: red;
            position: relative;
            animation-name: ${uniqueClass};
            animation-duration: 0s;
            animation-fill-mode: forwards;
        }`;
        document.head.appendChild(style);

        document.getElementById('cardsContainer').appendChild(card);

        fetchRatePlan(value.roomtypeid).then(rateplan => {
            document.getElementById(`rateplan-${value.roomtypeid}`).innerHTML = rateplan.str;
            document.getElementById(`room-occupancy-${value.roomtypeid}`).innerHTML = (rateplan.occupancy!==undefined ? rateplan.occupancy : 'Rates not defined');
        }).catch(err => {
            console.log('Error fetching rate plan:', err);
        });
    });
}


let initialSyncDone = false; // Flag to track if initial synchronization is done

function getRoomRatePlan(roomtypeid, noOfRoom, index, total_stay, extra_charge) {
    let currendate = new Date();
    let date = `${currendate.getFullYear()}-0${currendate.getMonth() + 1}-${currendate.getDate()}`
    fetch(`bookingajax.php?roomtypeid=${roomtypeid}&date=${date}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        },
    })
        .then(response => response.json())
        .then(data => {
            if (data && data.data && Array.isArray(data.data)) {
                const radioContainer = document.getElementById(`radio-container-${0}`);

                radioContainer.innerHTML = ''; // Clear the "Loading..." text

                data.data.forEach(item => {
                    const label = document.createElement('label');
                    label.style.cssText = 'display: flex; align-items: center; margin-bottom: 5px;';

                    const input = document.createElement('input');
                    input.type = 'radio';
                    input.name = `roomType-${0}`;
                    input.value = item.id;
                    input.style.cssText = 'margin-right: 8px; height: 14px;';
                    input.textContent = item.cm_rate_plan

                    // Add event listener to handle synchronization
                    input.addEventListener('change', () => {
                        syncRadioSelection(item.id);
                        handleRadioSelection(noOfRoom, item.fulldaytariff, total_stay, extra_charge);
                    });

                    const textNode = document.createTextNode(`${item.room_rate_plan} ₹${item.fulldaytariff}`);

                    label.appendChild(input);
                    label.appendChild(textNode);

                    radioContainer.appendChild(label);
                });
            } else {
                console.error('Unexpected data format:', data);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function handleRadioSelection(noOfRoom, fullDayTariff, total_stay, extra_charge) {
    document.querySelector('input[name="fixed-rate-input"]').value = `${extra_charge + (fullDayTariff * noOfRoom * total_stay)}`
}

// Function to synchronize radio selection across all cards
function syncRadioSelection(selectedId) {
    if (!initialSyncDone) {
        // Synchronize all radio buttons with the same value initially
        const radioButtons = document.querySelectorAll(`input[type="radio"][value="${selectedId}"]`);

        radioButtons.forEach(radio => {
            radio.checked = true;
        });

        initialSyncDone = true; // Set the flag to true after initial sync
    }
    // Allow the user to change individual selections afterward without synchronization
}


async function getFulldaytariffRatePlan(rateplanid, validfrom, validto) {
    try {
        const response = await fetch(`bookingajax.php?rateplanids=${rateplanid}&validfrom=${validfrom}&validto=${validto}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        });

        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error:', error);
        return null; // Return null in case of error
    }
}

function getCurrentFormattedDate() {
    const date = new Date();
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    const seconds = String(date.getSeconds()).padStart(2, '0');

    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
}

function formatDateTime(dateTimeString) {
    const dateObj = new Date(dateTimeString);

    const year = dateObj.getFullYear();
    const month = String(dateObj.getMonth() + 1).padStart(2, '0');
    const day = String(dateObj.getDate()).padStart(2, '0');

    return `${year}-${month}-${day}`;
}

function calculateTaxAmount(netAmount) {
    const taxRate = 0; // 12% tax rate
    const taxAmount = netAmount * taxRate;
    return taxAmount + netAmount;
}

function getDatesBetween(startDate, endDate) {
    let dates = [];
    let currentDate = new Date(startDate);
    endDate = new Date(endDate);

    // Ensure endDate is greater than startDate
    if (endDate <= currentDate) {
        return dates; // Return an empty array if the range is invalid
    }

    while (currentDate < endDate) {
        dates.push(new Date(currentDate).toISOString().split('T')[0]);
        currentDate.setDate(currentDate.getDate() + 1);
    }

    return dates;
}



async function fetchRoomRates(no_of_rooms, checkinDateBooking, checkoutDateBooking) {

    const name = `roomType-${0}`;
    const selectedRadio = document.querySelector(`input[name="${name}"]:checked`);
    const room_rate_plan = selectedRadio ? selectedRadio.value : null;

    let response = await getFulldaytariffRatePlan(room_rate_plan, checkinDateBooking, checkoutDateBooking)

    return response
}

function upload_proof() {
    console.log('inside');

    let formData = new FormData();
    formData.append('file', $('#fileinput')[0].files[0]);
    formData.append('action', "upload_document");

    $.ajax({
        type: 'POST',
        url: 'bookingajax.php',
        data: formData,
        processData: false, // Prevent jQuery from converting the data into a string
        contentType: false, // Prevent jQuery from overriding the content type
        success: function (response) {
            console.log('data', response);
        },
        error: function (err) {
            console.log('Error finding data', err);
        }
    });
}


async function bookRoom(hotelcode) {
    let first_name = document.querySelector('input[name="first_name"]').value;
    let last_name = document.querySelector('input[name="last_name"]').value;
    let contact = document.querySelector('input[name="contact"]').value;
    let email = document.querySelector('input[name="email"]').value;
    let country = document.querySelector('input[name="country"]').value;
    let state = document.getElementById('stateDropDown').value;
    let city = document.querySelector('input[name="city"]').value;
    let zip_code = document.querySelector('input[name="zip_code"]').value;
    let id_proof = document.getElementById('identityproof').value;
    let roomData = JSON.parse(document.getElementById('dataTo2ndModal').innerText);
    let date_of_payment = document.querySelector('input[name="date_of_payment"]').value;
    let txn_id = document.querySelector('input[name="txn_id"]').value;
    let cheque_bank = document.querySelector('input[name="cheque_bank"]').value;
    let cheque_no = document.querySelector('input[name="cheque_no"]').value;
    let cheque_date = document.querySelector('input[name="cheque_date"]').value;
    let amount_inp = document.querySelector('input[name="amount_inp"]').value;
    let comment = document.getElementById('commentTextarea').value;
    let mode_of_payment = document.getElementById('paymentModeDropdown').value;
    let actual_payment = document.querySelector('input[name="fixed-rate-input"]').value;
    let discount_type = mode_of_payment === "discount" ? document.getElementById('discountChoiceDropdown').value : "";
    let flat_discount = "";
    let percent_discount = "";
    let type_of_discount = ""

    const name = `roomType-${0}`;
    const selectedElement = document.querySelector(`input[name="${name}"]:checked`);


    if (discount_type === "flat") {
        flat_discount = document.querySelector('input[name="discount"]').value;
        type_of_discount = "flat";
    } else if (discount_type === 'percent') {
        percent_discount = document.querySelector('input[name="discount"]').value;
        type_of_discount = "percentage";
    }




    // Validation


    if (!first_name) {
        showMessageBoxuserdetails("First name is required.", "warning");
        return;
    }
    // if (!last_name) {
    //     showMessageBoxuserdetails("Last name is required.", "warning");
    //     return;
    // }
    if (!contact) {
        showMessageBoxuserdetails("Contact number is required.", "warning");
        return;
    }
    if (!email) {
        showMessageBoxuserdetails("Email is required.", "warning");
        return;
    }
    if (!country) {
        showMessageBoxuserdetails("Country is required.", "warning");
        return;
    }
    if (!state) {
        showMessageBoxuserdetails("State is required.", "warning");
        return;
    }
    if (!city) {
        showMessageBoxuserdetails("City is required.", "warning");
        return;
    }
    if (!zip_code) {
        showMessageBoxuserdetails("ZIP code is required.", "warning");
        return;
    }
    if (!id_proof) {
        showMessageBoxuserdetails("Select ID proof.", "warning");
        return;
    }

    const idProofInput = document.getElementById('fileinput-user-details-modal');
    const idProofFile = idProofInput.files[0]; // Get the first file (if any)

    // Check if a file is selected
    if (!idProofFile) {
        showMessageBoxuserdetails("Upload ID proof.", "warning");
        return;
    }




    if (!roomData || !roomData.checkinDateBooking || !roomData.checkoutDateBooking) {
        showMessageBoxuserdetails("Check-in and check-out dates are required.", "warning");
        return;
    }

    if (contact.length < 10 || contact.length > 12 || isNaN(contact)) {
        showMessageBoxuserdetails("Please enter a valid contact number.", "warning");
        return;
    }


    if (!selectedElement) {
        showMessageBoxuserdetails("Please select a Rate plan.", "warning");
        return;
    }


    if (mode_of_payment === 'neft/rtgs/imps' || mode_of_payment === 'upi' || mode_of_payment === 'credit/debit card' || mode_of_payment === 'online') {
        let txn_id = document.querySelector('input[name="txn_id"]').value;
        let date_of_payment = document.querySelector('input[name="date_of_payment"]').value;
        if (!txn_id || !date_of_payment || !amount_inp) {
            showMessageBoxuserdetails("Please fill in all transaction details for the selected payment mode.", "warning");
            return;
        }
    } else if (mode_of_payment === 'cash') {
        let date_of_payment = document.querySelector('input[name="date_of_payment"]').value;
        if (!date_of_payment || !amount_inp) {
            showMessageBoxuserdetails("Please provide the payment date and amount for cash payments.", "warning");
            return;
        }
    } else if (mode_of_payment === 'cheque/dd') {
        let cheque_bank = document.querySelector('input[name="cheque_bank"]').value;
        let cheque_no = document.querySelector('input[name="cheque_no"]').value;
        let cheque_date = document.querySelector('input[name="cheque_date"]').value;
        if (!cheque_bank || !cheque_no || !cheque_date || !amount_inp || !date_of_payment) {
            showMessageBoxuserdetails("Please provide all cheque details for cheque payments.", "warning");
            return;
        }
    } else if (mode_of_payment === 'discount') {
        let discount_type = document.getElementById('discountChoiceDropdown').value;
        let discount_value = document.querySelector('input[name="discount"]').value;
        if (!discount_type || !discount_value || !date_of_payment) {
            showMessageBoxuserdetails("Please provide the discount details.", "warning");
            return;
        }
    } else if (mode_of_payment === 'payatcheckout') {
        let date_of_payment = document.querySelector('input[name="date_of_payment"]').value;
        if (!date_of_payment) {
            showMessageBoxuserdetails("Please provide the payment date.", "warning");
            return;
        }
    } else if (mode_of_payment === 'writeoff') {
        let date_of_payment = document.querySelector('input[name="date_of_payment"]').value;
        if (!date_of_payment) {
            showMessageBoxuserdetails("Please provide the payment date.", "warning");
            return;
        }
    }




    let checkinDateBooking = formatDateTime(roomData.checkinDateBooking);
    let checkoutDateBooking = formatDateTime(roomData.checkoutDateBooking);
    let childBooking = Number(roomData.childBooking)
    let adultBooking = Number(roomData.adultBooking)
    let currentDate = getCurrentFormattedDate();
    let no_of_rooms = roomData.no_of_rooms;


    let alltariff = await fetchRoomRates(no_of_rooms, checkinDateBooking, checkoutDateBooking)
    let amountBeforeTax = 0

    console.log(alltariff);

    let rooms = []


    if (!selectedElement) {
        showMessageBoxuserdetails("Please select a Rate plan.", "warning");
        return;
    }

    const room_rate_plan = selectedElement.textContent;

    for (let i = 0; i < no_of_rooms; i++) {
        const dates = getDatesBetween(checkinDateBooking, checkoutDateBooking);
        const prices = dates.map(date => {
            amountBeforeTax += Number(alltariff.data[0].fulldaytariff)
            return {
                date: date,
                sellRate: Number(alltariff.data[0].fulldaytariff) // Assuming sellRate for each date is the same for simplicity
            }
        });
        rooms.push({
            "roomCode": roomData.cmroomtypename,
            "rateplanCode": room_rate_plan,
            "guestName": `${first_name} ${last_name}`,
            "occupancy": {
                "adults": adultBooking,
                "children": childBooking
            },
            "prices": prices
        });
    }
    let amountAfterTax = calculateTaxAmount(amountBeforeTax)

    const body = {
        "action": "book",
        "hotelCode": hotelcode,
        "channel": "banqueteasy",
        "bookingId": "111222333",
        "cmBookingId": "AAABBBCCC",
        "bookedOn": currentDate,
        "checkin": checkinDateBooking,
        "checkout": checkoutDateBooking,
        "segment": "Direct",
        "specialRequests": "Airport",
        "pah": false,
        "payment_type": mode_of_payment,
        "date_of_payment": date_of_payment,
        "txnid": txn_id,
        "discount_type": type_of_discount,
        "flat_discount": flat_discount,
        "percent_discount": percent_discount,
        "cheque_bank": cheque_bank,
        "cheque_no": cheque_no,
        "cheque_date": cheque_date,
        "comment": comment,
        "amount_inp": amount_inp,
        "amount": {
            "amountAfterTax": actual_payment,
            "amountBeforeTax": actual_payment,
            "tax": (0),
            "currency": "INR"
        },
        "guest": {
            "firstName": first_name,
            "lastName": last_name,
            "email": email,
            "phone": contact,
            "address": {
                "line1": "51",
                "city": city,
                "state": state,
                "country": country,
                "zipCode": zip_code
            }
        },
        "rooms": rooms
    }

    console.log(JSON.stringify(body));
    toggleSubmitAndImage('submitAnchor', 'fancybox/source/fancybox_loading.gif');


    fetch(`update_reservation.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            // Include any additional headers your API requires here
        },
        body: JSON.stringify(body)
    }) 
        .then(response => response.json())
        .then(data => {
            // Handle the response from the API
            console.log('Success:', data);
            let formData = new FormData();
            formData.append('file', $('#fileinput-user-details-modal')[0].files[0]);
            formData.append('cidt', checkinDateBooking);
            formData.append('codt', checkoutDateBooking);
            formData.append('cmroomtype', roomData.cmroomtypename);
            formData.append('reg_date', currentDate);
            formData.append('id_proof', id_proof);
            formData.append('action', "upload_document");

            $.ajax({
                type: 'POST',
                url: 'bookingajax.php',
                data: formData,
                processData: false, // Prevent jQuery from converting the data into a string
                contentType: false, // Prevent jQuery from overriding the content type
                success: function (response) {
                    console.log(response);
                    toggleSubmitAndImage('submitAnchor', 'fancybox/source/fancybox_loading.gif');
                    showMessageBoxuserdetails("Room Booking Completed", "success")
                    setTimeout(() => {
                        window.parent.location = "admin.php?Pg=bookingmap"
                    }, 1000);

                },
                error: function (err) {
                    console.log('Error finding data', err);
                }
            });

        })
        .catch(error => {
            // Handle any errors
            console.error('Error:', error);
        });

}

  const textarea = document.getElementById('autoTextarea');
  
  textarea.addEventListener('input', () => {
    textarea.style.height = 'auto'; // Reset height
    textarea.style.height = textarea.scrollHeight + 'px'; // Set to scroll height
  });

