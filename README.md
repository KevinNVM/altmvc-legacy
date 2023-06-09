# AltMVC Legacy For PHP 5.2+

## Project Description

This is a clone of my original AltMVC Framework made for PHP 5.2 and up. This framework may still has a lot of bugs and flaws since it uses older PHP version and i made it just for fun, and i also curious what older PHP version look like.

The syntax and usage look very similiar to the original AltMVC framework which also copy the Laravel syntax. This framework also doesn't have any third-party dependencies because again composer doesn't work on older PHP, so it's all handmade. Enjoy!

## Note

On linux, you may need to change the directory permission to allow the web server to write to the sqlite file. ex:

```bash
sudo chmod -R u+w,g+w,o+w <directory>
```

## Usage
```bash
# Since PHP 5.2 doesn't have built-in web server yet, just use regular web server like xampp or lampp

# Clone this repo
$ git clone https://github.com/kevinnvm/altmvc-legacy.git
```
Place the folder to htdocs folder or your web server directory, and then open the site.

## Credits
- [KevinNVM](https://github.com/kevinnvm)

> Please never use old PHP version it's literally a nightmare. 

[Original Project](https://github.com/kevinnvm/altmvc)
