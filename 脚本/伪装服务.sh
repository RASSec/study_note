#!/bin/bash
i=1
while [ $i -eq 1 ]
do 
	 nc -FNlp 80  < 1.html &> /dev/null
done	