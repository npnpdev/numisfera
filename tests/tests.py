import requests
from requests.auth import HTTPBasicAuth

api_url = "http://localhost:8080/api"
api_key = "LZJ7Z5W77RMZ11PQGWF6RDD4FUYH45T1"

response = requests.get(
    f"{api_url}/languages/1",
    auth=HTTPBasicAuth(api_key, '')
)
print(response.text)

