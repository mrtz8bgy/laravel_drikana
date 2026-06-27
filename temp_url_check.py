import urllib.request
resp = urllib.request.urlopen('http://localhost/laravel_drikana/public/')
print(resp.geturl())
print(resp.status)
print(resp.headers.get('Content-Type'))
print(list(resp.headers.items())[:10])
print(resp.read(20))
