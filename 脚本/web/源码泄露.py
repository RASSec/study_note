# -*- coding: utf-8 -*- 

import requests
import winsound
import ctypes
import sys
import socket
import socks

socks.set_default_proxy(socks.SOCKS5, "127.0.0.1", 1080)#设置代理
socket.socket = socks.socksocket #设置代理

# config-start
timeout = 5
website = sys.argv[1] # 注意格式 , 一定要加上后面的斜杠
# config-end

if not website.endswith("/"):
    website += "/"

STD_INPUT_HANDLE = -10  
STD_OUTPUT_HANDLE= -11  
STD_ERROR_HANDLE = -12  
  
FOREGROUND_BLACK = 0x0  
FOREGROUND_BLUE = 0x01 # text color contains blue.  
FOREGROUND_GREEN= 0x02 # text color contains green.  
FOREGROUND_RED = 0x04 # text color contains red.  
FOREGROUND_INTENSITY = 0x08 # text color is intensified.  
  
BACKGROUND_BLUE = 0x10 # background color contains blue.  
BACKGROUND_GREEN= 0x20 # background color contains green.  
BACKGROUND_RED = 0x40 # background color contains red.  
BACKGROUND_INTENSITY = 0x80 # background color is intensified.  

def check(text):
    if "Upload Labs" in text:
        return 0;
    return 1;
class ColorPrinter:  
    ''''' See http://msdn.microsoft.com/library/default.asp?url=/library/en-us/winprog/winprog/windows_api_reference.asp 
    for information on Windows APIs.'''  
    std_out_handle = ctypes.windll.kernel32.GetStdHandle(STD_OUTPUT_HANDLE)  
      
    def set_cmd_color(self, color, handle=std_out_handle):  
        """(color) -> bit 
        Example: set_cmd_color(FOREGROUND_RED | FOREGROUND_GREEN | FOREGROUND_BLUE | FOREGROUND_INTENSITY) 
        """  
        bool = ctypes.windll.kernel32.SetConsoleTextAttribute(handle, color)  
        return bool  
      
    def reset_color(self):  
        self.set_cmd_color(FOREGROUND_RED | FOREGROUND_GREEN | FOREGROUND_BLUE)  
      
    def print_red_text(self, print_text):  
        self.set_cmd_color(FOREGROUND_RED | FOREGROUND_INTENSITY)  
        print(print_text  )
        self.reset_color()  
          
    def print_green_text(self, print_text):  
        self.set_cmd_color(FOREGROUND_GREEN | FOREGROUND_INTENSITY)  
        print(print_text)
        self.reset_color()  
      
    def print_blue_text(self, print_text):   
        self.set_cmd_color(FOREGROUND_BLUE | FOREGROUND_INTENSITY)  
        print(print_text)  
        self.reset_color()  
            
    def print_red_text_with_blue_bg(self, print_text):  
        self.set_cmd_color(FOREGROUND_RED | FOREGROUND_INTENSITY| BACKGROUND_BLUE | BACKGROUND_INTENSITY)  
        print(print_text)  
        self.reset_color()     

colorPrinter = ColorPrinter()

listFile = open('list.txt', 'r')

urls = []

for i in listFile:
    i = i[0:-1]
    if "?" in i:
        fileFile = open('file.txt', 'r')
        for j in fileFile:
            j = j[0:-1]
            temp = i.replace("?",j)
            urls.append(website + temp)
    else:
        urls.append(website + i)

for url in urls:
    try:
        print("Checking : ")
        response = requests.get(url,timeout = timeout)
        if response.status_code == 200 and check(response.text):
            winsound.Beep(1000,1000)
            colorPrinter.print_green_text(url + '\tOK!')
            # if "404" in response.text:
            #   colorPrinter.print_blue_text(url + "\tMaybe every page same!")
        else:
            colorPrinter.print_red_text(url + "\tError")
    except Exception as e:
        print(e)