const {chromium}=require('playwright');(async()=>{const b=await chromium.launch();const p=await b.newPage();
for(const k of ['cat','dog']){await p.goto('file://'+process.argv[2]+'/first-hunt-guide-'+k+'.html');
await p.pdf({path:process.argv[2]+'/../first-hunt-guide-'+k+'.pdf',format:'Letter',printBackground:true});}
await b.close();})();