Steps to install:

```
npm install express pdf-to-printer body-parser
```



Option 1: Use NSSM to Create a Windows Service
1. Download and Install NSSM (Non-Sucking Service Manager)

Download NSSM from here.
Extract the downloaded file to a folder (e.g., C:\nssm).
2. Create a Service with NSSM

Open Command Prompt with Administrator privileges.
Navigate to the folder where NSSM is located (e.g., cd C:\nssm).
Run the following command to create a new service:
nssm install PrintServer "C:\Program Files\nodejs\node.exe" "C:\path\to\your\print-server.js"
Replace "C:\path\to\your\print-server.js" with the actual path to your print-server.js file.
This command will create a service called PrintServer that will run the Node.js script when the machine starts.
3. Configure the Service to Start Automatically

After running the above command, NSSM will open a configuration window.
In the "Shutdown" tab, select Graceful Shutdown or leave the default setting.
Click Install service.
4. Start the Service

Once installed, start the service by running the following command:
nssm start PrintServer
5. Verify Service is Running

To verify the service is running, go to the Services window (services.msc).
Look for the PrintServer service and make sure it is Running.
Option 2: Use PM2 to Run Node.js in the Background
1. Install PM2 Globally

PM2 is a popular Node.js process manager that you can use to keep your Node.js application running in the background.

npm install pm2 -g
2. Start the Print Server with PM2

Navigate to your print-server.js directory and start the application using PM2:

pm2 start print-server.js --name "PrintServer"
3. Save the PM2 Process List

Run this command to ensure PM2 automatically restarts the service after a reboot:

pm2 startup
This will provide you with a command to run based on your environment (usually pm2 startup windows).

4. Save the PM2 Process List for Auto Restart

pm2 save
This will save the current PM2 processes so that they will be restarted automatically on machine reboot.

5. Start the Print Server Automatically on Boot

PM2 will automatically start the print-server.js process when the Windows machine reboots.

Step 2: Testing and Troubleshooting
Test if it starts after reboot: Restart the machine and check if the print server is running as expected.
Check PM2 logs: If you’re using PM2, you can view the logs with the command:
pm2 logs PrintServer
Check Service Logs: If you’re using NSSM, you can check the service logs in the C:\ProgramData\nssm\ folder for troubleshooting.
Step 3: Automatically Start on Windows Startup
If you want to make sure that NSSM or PM2 automatically starts when the Windows machine starts, ensure that:

NSSM has been set up with "Start Type" as Automatic in the service properties.
PM2 has the pm2 startup and pm2 save commands configured.




Other options:
4. Auto-start on System Boot
If you want this server to run in the background and start automatically on machine boot, follow one of these methods:

On Windows:

Create a .bat file:
Open Notepad, and paste this:
@echo off
cd /d "C:\path\to\print-server"
start /min node print-server.js
Save it as start-print-server.bat
Add it to Startup (Win + R → shell:startup → Paste the .bat file there).
On Linux/macOS (with systemd)

Create a systemd service:
sudo nano /etc/systemd/system/print-server.service
Add this content:
[Unit]
Description=Print Server
After=network.target

[Service]
ExecStart=/usr/bin/node /home/your-user/print-server/print-server.js
Restart=always
User=your-user
Environment=NODE_ENV=production

[Install]
WantedBy=multi-user.target
Enable and start the service:
sudo systemctl enable print-server
sudo systemctl start print-server
5. Run in Background Manually
If you just need to keep it running without auto-start:

nohup node print-server.js > output.log 2>&1 &
or use pm2:

npm install -g pm2
pm2 start print-server.js --name print-server
pm2 save
pm2 startup


