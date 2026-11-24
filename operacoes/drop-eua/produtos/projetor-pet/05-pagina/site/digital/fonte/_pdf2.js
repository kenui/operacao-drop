const {chromium}=require('playwright');(async()=>{const b=await chromium.launch();const p=await b.newPage();
await p.goto('file://'+process.argv[2]+'/rainy-day-play-plan-dog.html');
await p.pdf({path:process.argv[2]+'/../rainy-day-play-plan-dog.pdf',format:'Letter',printBackground:true});await b.close();})();