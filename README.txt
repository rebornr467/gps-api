GPS API (Advanced) - Ready for Render.com
----------------------------------------

Files:
- gps.php       : main endpoint (POST JSON or form data). Fields: device_id, latitude, longitude
- database.php  : helper to create PDO connection from environment variables
- Dockerfile    : builds PHP + pdo_pgsql
- render.yaml   : Render service config (includes DB env vars)

Usage:
- Deploy this repo to Render (connect GitHub repo)
- After deploy, POST JSON to https://<your-render-app>/gps.php
  Example JSON:
    { "device_id":"RFID001", "latitude":14.288709, "longitude":121.109105 }

- Or use form-encoded:
    device_id=RFID001&lat=14.288709&lon=121.109105

Security:
- Keep DB credentials secret. You can override env vars in Render dashboard.

