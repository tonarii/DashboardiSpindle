iSpindle Dashboard is a Bootstrap tool to manage and visualize data about your iSpindle.
You can display Density, Temperature, Battery life, Wifi and many other things and export your database data to a csv file.
It's intended to work with a database, but you can tweak it to read data from a csv file instead.
Also it's in French, but you can easily translate this in English or whatever inside each php files.
Enjoy. -Nikko-

ps : i'm not a dev, so the code is not clean and there's still unnecessary stuff inside.
Might clean that in the future, or might not ^^


## NEW BIG UPDATE

- Now the pages " Angle", "Densité" and "Température" are showing the data from the first data recorded on your database
- The curves charts of these pages are now zoomable, you can precisely see the data from a specific time period
  (You just have to "select" a zone with your mouse. Also a "reset" button will appear to go back to the normal view)
- The curves charts of the "Dashboard" page only displays the data on a 24 hour time period
- Inside the settings page "Réglages", you can now choose which iSpindel you want to show the data of. For instance you can name your iSpindels like this : iSpindel000 , iSpindel001, iSpindel002...
- The option to delete the database table, now only erase the current iSpindel in use. You need to select it in the settings panel. Better save your database before using this function


## VERSION

- V 1.1.5 (Optimizations)
- V 1.1.4 (Added the new functions inside the public release)
- V 1.1.3 BETA (Fixed few bugs, still working on some other problems)
- V 1.1.0 BETA (Big update with new functionalities)
- V 1.0.6 (Fixed PHP 7 compatibility issue)
- V 1.0.5 (updated the code to quit using cdn for local network users)
- V 1.0.4


## How to

- You need to creat the Data table inside your database, use the model inside the package
- Then inside your iSpindle configuration page, for "Service Type" use HTTP, for "Server Adress" enter your website adress (ex: mywebsite.com) and for "Server URL" your folder url (ex: /myfolder/)
- Edit index.php file and common_db.php (inside assets) with your database informations
- You also have to edit csvexport.php file with the same informations and can change this "$f = fopen('php://memory', 'w');" to this "$f = fopen('../csv/FILE_NAME.csv', 'w');" if you also want to export your csv to your ftp
- Edit line 57 on settings.php and replace "MONPASSWORD" with the password you want to use (don't use the same as the one for your database !)
- Send everything to your ftp  (keep the files structure !)
- Go to your dashboard page and change settings in "reglages", sorry it's in French but you can translate this in English or whatever inside each php files


## Licensing

- Everything is tweaked and mixed by Nikko
- Base of work by DottoreTozzi (https://github.com/DottoreTozzi/iSpindel-TCP-Server)
- Dashboard base code by Creative Tim (https://www.creative-tim.com/)
  | Licensed under MIT (https://github.com/creativetimofficial/black-dashboard/issues/blob/master/LICENSE.md)
- Charts by Highcharts (https://www.highcharts.com) and Fusioncharts (https://www.fusioncharts.com)


## Useful Links

- [iSpindel](https://github.com/universam1/iSpindel)
- [TCP Server](https://github.com/DottoreTozzi/iSpindel-TCP-Server)

## PICTURES

![Screenshot](DeleteMe.gif)
