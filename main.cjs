const fs = require('fs');
const url = 'https://m.dana.id/n/dana-transaction/v2/list';

const headers = {
    'sec-ch-ua': '"Android WebView";v="141", "Not?A_Brand";v="8", "Chromium";v="141"',
    'sec-ch-ua-mobile': '?1',
    'sec-ch-ua-platform': '"Android"',
    'upgrade-insecure-requests': '1',
    'user-agent': 'Mozilla/5.0 (Linux; Android 14; JDY-LX2 Build/HONORJDY-L52; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/141.0.7390.124 Mobile Safari/537.36Ariver/1.0.0 Griver/2.77.0 AppContainer/10.5.10 Skywalker Skywalker/2.103.1 EDIK/1.0.0 Dalvik/2.1.0 (Linux; U; Android 14; JDY-LX2 Build/HONORJDY-L52) Ariver/2.77.0 LocalKit/1.5.1.3  Lang/en-US AlipayConnect iapconnectsdk/2.50.0',
    'accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
    'x-requested-with': 'id.dana',
    'sec-fetch-site': 'same-origin',
    'sec-fetch-mode': 'navigate',
    'sec-fetch-dest': 'document',
    'referer': 'https://m.dana.id/i/transaction/list/completed',
    'accept-encoding': 'gzip, deflate, br, zstd',
    'accept-language': 'id-GB,id;q=0.9,en-GB;q=0.8,en-US;q=0.7,en;q=0.6',
    'cookie': 'userId=216610000955852996460; session.cookieNameId=ALIPAYJSESSIONID; ak_bmsc=FF35762C6611A7F627B99E65F5CB2F32~000000000000000000000000000000~YAAQ66zbF4kZFHmaAQAAC9nDkR2h8PSOvgrn1vtzGXM/DBNHC9wJQrpXk1u4ungAd7gspH6zb6oY1EgdI5ZwU9ZdD6veRKpn1Ia3yx3/j3VkuXNN0/JzTrEQRNwUBAuQGkfHGxrDQSajxnwaoYqAW9KT+MuRECvhrVKFD05LV0aFWpVdsd8ShdFUjs6gdoH+w7b/jHrlJ91aOd33sF8CL0+lJLazreLciQadOjnIE42cXESKComoeQ2fwb2AOXHBV9/smtar3cQS6OKQIAHC10UQXbBBO+D9HgGRw8CaxOnKBKPT2gKiZDJMjbmfYIrwhFqFaySvksF+NJop5zvB8QtQH481Ig4vVLIv/w7CdDjWL7oKBafwXD40+lrRTAcu/gCpGVsZLg7bTCZ/f7+Ze2ftrTs8YHyKZrno+JK//qrmMGk9jSvfhI2xAtdcEHiX6jqBPSkQbUBKHdBJ; oneDayId=3837605081; TRANSACTION_ONBOARDING=true; ALIPAYJSESSIONID=GZ00F28FA6F7C2504EB39ABD3F7A0EC1C9F0aphomeGZ00; ctoken=_j5YSSl_quv6udp8; AKA_A2=A; bm_sv=5518BE639F85B2005CE3BB755FB9D3C9~YAAQBR0gF+bXiHqaAQAAK2gskh0rtshhVfeyd9pDvBvbAq+inSe2kttTT6RaUa+kwW10DJ9USC/S17dpN/qnSwYYk6ccLx2jTcNt13BAw5hOOKvQWYDtmdW14ONqvOQ5OLumeJFxW7GnBO7WfDsIrmH/tAywUkspowEunc8peHk/sEJx85J6jZx8ir7PipmfMl13AxtbQKoyI0uqy39gRe0vtC3XFgkpbB9G7RC+fI+/ClEdP5H5ZLF7AJ8alA==~1; bm_sz=9863DAF3409CC056B63E33DCF4165A3C~YAAQBR0gF+fXiHqaAQAAK2gskh2A/HatkSQj6NCSAF2gKfaTVtI7fv/TAgp5d0GeoVsls2HvDt9n0IMHvIVJZCwjseUdXuPlRMN/08G4H8CZ49FA2LpKeT5k6UlOgasOZRr7qDXbGkT260F4BT25NrFWWgyOf2ivCzshV4swDoZnYIqzqfLQbGOi5qCcNI9v9tJh/uXulE4LUmYUU4f4tJRPL4mSzAxy8HSnZjHkBNaljRzGalOzKnQX6BgnAVBSBRWHyEZOJD14Dyavys6YkE94sMYg4yYsg+p46Qlbf7WMveZgIdcoANtv8MLNnYK51grkEesOd10HFk5xpOfvRZ02II2R3iq0y1QTPBEQEh0izE06DOJaQwkPsKRm1EPOULFs0+ieWNITQpXnRn7Na5IWgKDpukMT68JGu2NtanXPPZfBK8w2IAOa5mKLyX1HSEjsO4xIX+SdemMDOKcbTaSyICjMqfqT39J5YvynibLhNg==~3225650~4600644; _abck=8FD1E576D50584E0E475F778E2CD0BAD~-1~YAAQBR0gF03ZiHqaAQAA8G8skg7uYnu6Ss+/sq535eZkBoCgOjotmikA9kmxsePYg7mHZWbc4ZvAMX6tUtJIs5IKn/Sk4VloJMM6Y/pdNFHS2UKl+3J9/7G58YRimPBRlwxtZhoDLel/St3whJZMqD4vQ5sAonsXdEV9SgtSvsqt97gQYSUX7XmncnJ9ln9ay4K0qc8qnHHtNr+cqAGhYwMHLt7xrlwd0AJNxOm0dsk7mrNjKSJ0q3OEtpTBMVJPu8y6u6bZya2C75RbAzcDAjMFjpNVsLPDXSPWUhPvCUT79dFeFeXhIIxdD0xnQGUCwtnvCN/UO3chIjnaToums7ux0VWOfUyZbfeTIxGZyPcZmM3COklmGjJsIasoTIRXDx5Iw6urQ5NQRr2zM8/f2IRvnUi5BgL+n78Vu2t+wQPN26k/0XFeTZqWggKHs7BLiuQXPRD1F6J9bAT6twCM4dR77bZ2RcaRPBxU+R6u6g1PoVjivI8dMm1DUrRU4+oE0A22Kgfljf++dVGLWuSaFqhufXpSd0cEbwdBWsOstRl11n+LL2sW6D9AcY/tE7E4GC1NSc3/lL2ANucQNhpADi81710ZDQXQgKa16ERnpoUXGibu339vP+z05g4sJ8BfqWQ3BzqRdQ+H97Kyib2p8Z2ufh+j+t6CxYQAnLXNcKzfQ8d9MFKXUvIKZEbFX6lQrtKoEpbTuAFJUpqMihfXKuZ8sUkA3NpsVqQDbHPxmUofPG+YUQ==~-1~-1~-1~AAQAAAAE%2f%2f%2f%2f%2fx1f1FdNviPvzBpft+7SB4o+iE9efbY+S6908ZpBcYWloU+qyNau+P%2fh++Pxcx0iEXxvAMo0bx1UGzxYJ0PnLMFgQgret1HJMkDHLuKv0DZFW9JI8zKeAM%2f7XWbKIkpphWnaYwLM6UeNOfgl6SI+LG+Y6xULz9KYXJv3qxv6KzN95SRKRQH2t6Pkz69MnQeBK5jMJnc4ZjF1~-1; RT="z=1&dm=m.dana.id&si=23b73ec8-2fad-49f5-8998-b9962960fcb8&ss=mi346qjv&sl=6&tt=1v9&obo=5&rl=1&ld=42xea&r=351e2o4w&ul=42xec"'
};

fetch(url, {
    method: 'GET',
    headers: headers
})
    .then(response => response.text())
    .then(data => fs.writeFileSync('dana_transactions.html', data))
    .catch(error => console.error('Error:', error));
