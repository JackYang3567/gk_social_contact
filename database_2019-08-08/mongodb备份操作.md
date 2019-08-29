
mongodump -h 47.112.126.236  --username=my_user --password=my_user_password_741852  

mongodump -h 47.112.126.236:16906 -u my_user -p my_user_password_741852 -d im -o /tmp/dump/data


mongo -h 47.112.126.236:16906 --username=my_user --password=my_user_password_741852