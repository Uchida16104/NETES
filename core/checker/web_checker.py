import urllib.request

def check():
    try:
        urllib.request.urlopen("https://www.google.com", timeout=5)
        return True
    except:
        return False
