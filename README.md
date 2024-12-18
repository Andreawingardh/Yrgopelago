# YRGOPELAGO Assignment
## NEEDS:
- [ ] Create an island. Pick from your own imagination or check out a list of fictional islands
- [ ] Find a name for your hotel.
- [ ] Setup a subdomain for your site, preferably on your one.com-account.
- [ ] Use your startcode at the central bank of Yrgopelago to get your own, secret and extremly valuable API_KEY. Please keep it in the .env in your project.
- [ ] Build a tiny website or just a web page for your hotel (see requirements below)
- [ ] Register your island and hotel at the central bank of yrgopelago using your API_KEY
- [ ] Create a database for your hotel, so that you can store information about your visitors arrival and departure date, which room they will be staying in and such information.

## REQUIREMENTS:
Below you'll find a list of requirements which need to be fulfilled in order to complete the project.

- [ ] The application should be developed using HTML, CSS, SQL and PHP. Add a bit of javaScript if needed.
- [ ] Only desktop. No mobile.
- [ ] The application should be using a SQL (sqlite, MySQL etc) database.
- [ ] The application should be pushed to a repository on GitHub. Please enter the url in your yrgopelago.md in your feedback/grade-repo. If private, you should invite hassehulabeck as collaborator.
- [ ] The project should declare strict types in files containing only PHP code.
- [ ] The project should not include any coding errors, warning or notices.
- [ ] The repository should have at least 20 commits and you have to commit at least once every time you're working on the project.
- [ ] The repository must contain a README.md file with a description of the project and possibly instructions for installation (if needed).
- [ ] The repository must contain a LICENSE file.
- [ ] You must follow the four hotel rules below
- [ ] The project must receive a code review by another student. Add at least 7 comments to the student's README.md file through a pull request. Give feedback to the student below your name. The last student gives feedback to the first student in the list. Add your feedback after lunch at january 10th. A code review line could look like this:

## RULES:

- [ ] Every hotel has exactly three single rooms (budget, standard and luxury), so you can only have three guests at the same time.
- [ ] As a manager, you set the price for your three rooms, but you should probably adjust the price according to the room standard and the star rating of the hotel. The more stars, the higher the cost.
- [ ] The hotel website must have a form where visitors can book a room.
- [ ] As a manager, you will check for how many stars your hotel is qualified to, and the hotel website should display this info.

## STARS:
- [x] ☆ The hotel website has a graphical presentation of the availibility of the three rooms. (There's some nice packages that can simplify that part. Try to google php package calendar
- [ ] ☆ The hotel can give discounts, for example, how about 30% off for a visit longer than three days?
- [ ] ☆ The hotel can offer at least three features that a visitor can pay for. You can create your own features, but checkout the different features that are listed at Awards - Points for the tourist, as they will be more valuable for the tourists. Note: A hotel cannot offer all the features that makes an accepted set. (For example, your hotel cannot offer bicycle, unicycle and rollerblades).
- [ ] ☆ The hotel has the ability to use external data (images, videos, text etc) when producing succesful booking responses that the customers get.
- [ ] ☆ The hotel manager has an admin.php page - accessible only by using your API_KEY - where different data can be altered, such as room prices, the star rating, discount levels and whatever you can think of.

## HOTEL BUILD INSTRUCTIONS
- [ ] Our hotel MUST give a response to every succesful booking. The response should be in json and MUST have the following properties:
```
    island
    hotel
    arrival_date
    departure_date
    total_cost
    stars
    features
    additional_info. (This last property is where you can put in a personal greeting from your hotel, an image URL or whatever you like.)

{
  "island": "Main island",
  "hotel": "Centralhotellet",
  "arrival_date": "2025-01-12",
  "departure_date": "2025-01-17",
  "total_cost": "12",
  "stars": "3",
  "features": [
    {
      "name": "sauna",
      "cost": 2
    }
  ],
  "addtional_info": {
    "greeting": "Thank you for choosing Centralhotellet",
    "imageUrl": "https://upload.wikimedia.org/wikipedia/commons/e/e2/Hotel_Boscolo_Exedra_Nice.jpg"
  }
}
```
- [x] The booking calendar MUST be fixed to show only january 2025. Use attributes min and max in the input.

- [x] For a bit of simplicity, all bookings is only stored as whole days, meaning that a tourist books a room the entire day.

- [ ] Your hotel MUST check availibilty of the requested room and dates before making the booking and sending the response package as json.

- [x] Your hotel MUST check if a transferCode submitted by a tourist is valid (otherwise you won't get any money)

## INFO:
```
$acceptedSets: [
  ["bathtub", "pool", "sauna"],
  ["pinball game", "ping pong table", "PS5"],
  ["bicycle", "unicycle", "rollerblades"],
  ["coffeemaker", "waterboiler", "mixer"],
  ["rubiks cube", "deck of cards", "yatzy"],
  ["gun", "rifle", "small cannon"],
  ["tv", "radio", "speakers"],
  ["minibar", "bar", "superior bar"]
]);
```

# BUILD:

## Back-end:

### Structure
- [x] Set up dev branch on GITHUB
- [ x Create structure (inspiration from lesson on project structure)
    - [ ] Look into $config and autoload.php
    - [ ] Keep working on structure, it's currently not very good **Week 2**
- [x] Get API-key and put it in .env-file
    - [x] Add .env file to .gitignore


### Database
- [x] Test database construction (test)
    - [ ] Evaluate and improve if necessary
- [ ] Update database according to results from test-run **Week-2**
- [x] Create test connection with website
- [x] Think about how to create a junction table between features and booking

### Calendar
- [x] Find a calendar package and implement it (test)
- [x] Connect to database
- [x] Create a function to show availability in calendar

### Form
- [ ] Present result in JSON format
- [ ] Send result to database
    - [ ]  Room data
    - [ ]  Feature data
    - [ ]  Dates  
- [ ] Create if-clauses to check: **Week 1**
    - [x] Availability
        - [x] Connect with database
    - [x] Transfer code
        - [x] Fetch API from Yrgocentralbanken.
        - [x] Check transfer code with transferCode database endpoint
        - [x] Deposit transfercode in deposit endpoint
- [ ] Do a calculation of total_cost **Week 2**
- [ ] Create a separate form for withdraw function?

### Functions
- [x] Test functions page (test)
- [ ] Change code into functions and add to booking.php **Week 2**

### ETC

## Front-end:
### MARKUP
- [ ] Build form in markup with relevant inputs (test)
    - [x] Arrival
    - [x] Departure
    - [x] Room
    - [x] Features
        - [ ] Add real features into markup and database **Week 2**
    - [ ] Sum
        - [ ] Figure out how to show total cost before form is submitted. Javascript?? **Week 2**
    - [ ] 
### CSS
- [ ] 

### Javascript
- [ ] 

### ETC
- [ ] Decide on name of island and hotel

