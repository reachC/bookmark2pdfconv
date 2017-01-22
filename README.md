With this php-cli script you can download all your firefox bookmarks and save it into pdf files.
This project uses the mPDF library for php.

###How to install:
- Download this repository with git or as a zip file: git clone https://github.com/reachc/bookmark2pdfconv
- You need write permission to the output folder
- Install all needed php packages (Ubuntu/Debian: sudo apt-get install php7.0 php7.0-cli php7.0-mbstring php7.0-xml)
- The default layout of the pdf files is "A3 landscape". You can change this at the line "$mpdf = new mPDF('utf-8', 'A3-L');".

###How to use:
- Export your firefox bookmarks into a json file: "Bookmark" -> "Show all Bookmarks" -> "Import and Backup" -> "Backup"


```
$ php bookmark2pdfconv.php bookmarks.json 
Found 3 bookmarks
[1/3] reachcodingeuarduinotimerinterrupt.pdf
[2/3] dewikibooksorg.pdf
[3/3] enwikipediaorg.pdf

Failed: 0
```
