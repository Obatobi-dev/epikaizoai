###### Application struction UI / UX
### User
* User sign up
- Email
- Full name
- Password
- Password again

* Login
- Email
- Password

* Auth
- mail will be sent to users dashboard upon registeration

* Dashboard
- Trade
Histoy
Binary trading (Max must not be more than $20,000)
- Withdrawal (USDT) Either in scan or Wallet address
- Support


Ben258savvy369

### Admin
* User
Ability to disable, edit, login and update a user account
Manage withdrawal
Create post for user to earn from
* Sms
Send user mails
Reply to support
* Funds
Ability to control and manage funds
Create, update and delete Registeration code


### General
* Color
Primary: Teal
Secondary: ''
* Font
Jost
Poppins





# Session timeout, 2628000 sec = 1 month, 604800 = 1 week, 57600 = 16 hours, 86400 = 1 day
ini_set('session.save_path', '/home/server/.folderA_sessionsA');
ini_set('session.gc_maxlifetime', 57600); 
ini_set('session.cookie_lifetime', 57600);
# session.cache_expire is in minutes unlike the other settings above         
ini_set('session.cache_expire', 960);
ini_set('session.name', 'MyDomainA');



or put this in your .htaccess file.

php_value session.save_path /home/server/.folderA_sessionsA
php_value session.gc_maxlifetime 57600
php_value session.cookie_lifetime 57600
php_value session.cache_expire 57600
php_value session.name MyDomainA



// cryptope
// 7W52#ElGscH2*m
// 5cffd167-16c3-4c34-b345-6eef830ce5a3
// ddb845311a196fb7291ca457a33a75c2344ab78a65992cd0bf50ac15c35c98d2
// My coin markey
// 320380be779194c26167b97185a2fa9b29ebc8be49ca0b042632d6f7ae508884
// Coin market cap
// 701a5614-2b6c-4465-a597-c4ba0a6cf84e
// 3c9c68a0b53086223e63cd593e04d479
// ae2b1fca515949e5d54fb22b8ed95575
https://cdn.jsdelivr.net/gh/fawazahmed0/currency-api@1/latest/currencies/btc/usd.json
// Forex
https://financialmodelingprep.com/api/v3/quotes/forex?apikey=0967e2a5544602ccf161a8d8d14b0548
0967e2a5544602ccf161a8d8d14b0548
// Stock
https://financialmodelingprep.com/api/v3/quote/AAPL?apikey=0967e2a5544602ccf161a8d8d14b0548
// Stock image
https://g.foolcdn.com/art/companylogos/mark/TSLA.png
// Stock all ....
https://financialmodelingprep.com/api/v3/stock/list?apikey=0967e2a5544602ccf161a8d8d14b0548