# Mobile Testing Instructions

## Current Setup
- Laravel server is running on: http://192.168.0.108:8000
- Your computer's local IP: 192.168.0.108
- Server accessible from mobile devices on the same WiFi network

## Testing Steps

### 1. Access QR Code Page
- Open in your computer browser: http://192.168.0.108:8000/club-manager/attendance/events/1/qr-code
- You should see the QR code generated automatically

### 2. Test with Mobile Phone
- Make sure your phone is connected to the same WiFi network as your computer
- Scan the QR code with your phone's camera
- It should open: http://192.168.0.108:8000/attendance/qr/event_1_4dWgcrwoVaoO7qyPQOBG

### 3. Alternative Mobile Test
- Manually open your phone's browser
- Type: http://192.168.0.108:8000
- Navigate to the attendance check-in page

## Troubleshooting

### If Mobile Still Can't Connect:
1. **Check WiFi**: Ensure both devices are on the same network
2. **Firewall**: Your computer's firewall might be blocking connections
3. **Network Isolation**: Some routers have AP isolation enabled

### Firewall Solution (Windows):
- Open Windows Defender Firewall
- Allow "php.exe" through the firewall
- Or temporarily disable firewall for testing

### Alternative IP Addresses:
If 192.168.0.108 doesn't work, try:
- 192.168.56.1 (if on VirtualBox network)
- Check `ipconfig` for other active network adapters

## Quick Tests:
- Computer browser: http://192.168.0.108:8000 ✓
- Mobile browser: http://192.168.0.108:8000 (test this)
- QR code scan: Should open the check-in page

## Current Event Details:
- Event: Tuki
- Event ID: 1
- QR Code: event_1_4dWgcrwoVaoO7qyPQOBG
- Check-in URL: http://192.168.0.108:8000/attendance/qr/event_1_4dWgcrwoVaoO7qyPQOBG
