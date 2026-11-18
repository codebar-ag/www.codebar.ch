import re,sys,base64,subprocess,os
root=os.path.dirname(os.path.dirname(os.path.dirname(os.path.abspath(__file__)))); S=sys.argv[1]
js=open(S+'/snapshot.js',encoding='utf-8').read().replace('</script','<\\/script')
pages={'dienstleistungen':'expertise.html','erp/odoo/preise':'odoo-preise.html'}
def css_inline(m):
    href=m.group(1); path=root+'/public'+re.sub(r'^https?://[^/]+','',href)
    css=open(path,encoding='utf-8').read()
    def font(fm):
        p=root+'/public'+fm.group(1)
        if os.path.exists(p) and '-latin.' in p:
            return 'url(data:font/woff2;base64,'+base64.b64encode(open(p,'rb').read()).decode()+')'
        return 'url(../public'+fm.group(1)+')'
    css=re.sub(r'url\((/build/assets/[^)]+)\)',font,css)
    return '<style>'+css+'</style>'
for path,out in pages.items():
    h=subprocess.run(['curl','-sk','https://web.codebar.test/'+path],capture_output=True,text=True).stdout
    import json
    manifest=json.load(open(root+'/public/build/manifest.json'))
    entry=manifest['resources/js/app.js']
    files=list(entry.get('css',[]))
    if 'resources/css/app.css' in manifest: files.append(manifest['resources/css/app.css']['file'])
    styles=''.join(css_inline(re.match(r'(.*)','/build/'+f)) for f in dict.fromkeys(files))
    h=h.replace('</head>',styles+'</head>',1)
    h=re.sub(r'<link[^>]+rel="manifest"[^>]*>','',h)
    h=re.sub(r'<link[^>]+rel="(?:modulepreload|preload)"[^>]*>','',h)
    h=re.sub(r'<script\b[^>]*type="application/ld\+json"[^>]*>.*?</script>','',h,flags=re.S)
    h=re.sub(r'<script\b[^>]*>.*?</script>','',h,flags=re.S)
    h=h.replace('https://web.codebar.test/erp/odoo/preise','odoo-preise.html').replace('"https://web.codebar.test/dienstleistungen"','"expertise.html"')
    h=h.replace('https://web.codebar.test','https://www.codebar.ch')
    h=h.replace('</body>','<script>'+js+'</script></body>')
    open(root+'/concepts/'+out,'w',encoding='utf-8').write(h)
    print(out,len(h)//1024,'KB','herd' in h.lower(), h.count('<script'))
