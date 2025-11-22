# Lab 1 - Capture The Flag <!-- omit in toc -->

# Table of contents <!-- omit in toc -->
- [1. Requirements](#1-requirements)
- [2. Learning objectives](#2-learning-objectives)
- [3. Remarks](#3-remarks)
- [4. Preparation](#4-preparation)
- [5. Introduction](#5-introduction)
- [6. Connect to Teachers Network](#6-connect-to-teachers-network)
  - [Additional Ressources - Wi-Fi](#additional-ressources---wi-fi)
  - [Additional Ressources - DHCP-Client](#additional-ressources---dhcp-client)
- [7. WireGuard tunnel](#7-wireguard-tunnel)
  - [Additional Ressources - WireGuard](#additional-ressources---wireguard)
- [8. EoIP Tunnel](#8-eoip-tunnel)
  - [Additional Ressources - EoIP](#additional-ressources---eoip)
- [9. Capture The 🏴‍☠️](#9-capture-the-️)
  - [Additional Ressources - Bridge](#additional-ressources---bridge)

# 1. Requirements 
 - Teachers Setup to connect to (usually a WiFi or a Switch to plug in own device)
 - [MikroTik hAP ac lite](https://mikrotik.com/product/RB952Ui-5ac2nD) or similar device

# 2. Learning objectives
 - Understand the structure and security properties of a WireGuard tunnel.
 - Configure WireGuard on RouterOS (keys, interface, peers, allowed-IPs) and analyze tunnel operation.
 - Understand the encapsulation model of EoIP 
 - Configure an EoIP tunnel on RouterOS
 - Combine WireGuard and EoIP 

# 3. Remarks

# 4. Preparation
 - Update your device to the newest RouterOS 7.x (2025-11-21, 7.20.x)
 - Reset your device with no default configuration `/system/reset-configuration no-defaults=yes skip-backup=yes`

# 5. Introduction
<svg width="150" height="150" viewBox="130 50 140 140" xmlns="http://www.w3.org/2000/svg">
<rect x="148" y="50" width="4" height="140" fill="#5a5a5a"/>
<path d="
    M152 60
    C167 55, 182 65, 197 60
    C212 55, 227 65, 242 60
    L242 120
    C227 125, 212 115, 197 120
    C182 125, 167 115, 152 120
    Z"
    fill="#e63946"/>
</svg>

Your task is to reach the flag before anyone else. You must connect your router to the WiFi, establish the WireGuard tunnel, set up the EoIP tunnel, link your PC or laptop to the remote network, and access the target webserver in your browser.

![Overview](media/Overview.png)
<br>*Figure 1: Overview of the network.*

# 6. Connect to Teachers Network
Your course instructor will have a Wi-Fi network for you to connect your router. 

**💪 Challenge 1:** Connect your Router to the Wi-Fi network provieded by the course instructor. *Typically the SSID is `Capture The 🏴‍☠️`*

<details>
<summary>Solution</summary>
<p>Adjust SSID and pre-shared-key accordingly</p>
<pre>
/interface/wireless/security-profiles/add name=CTF authentication-types=wpa2-psk wpa2-pre-shared-key=1234 mode=dynamic-keys
/interface wireless set [ find default-name=wlan2 ] ssid="Capture The \F0\9F\8F\B4\E2\80\8D\E2\98\A0\EF\B8\8F" band=5ghz-n/ac frequency=auto mode=ap-bridge  wps-mode=disabled security-profile=CTF disabled=no
/ip dhcp-client add interface=wlan2
</pre>
</details>
<br/>

![DHCP-Client](media/dhcpclient.png)
<br>*Figure 2: DHCP client successfully acquired an IP address from the teacher’s router.*

## Additional Ressources - Wi-Fi

## Additional Ressources - DHCP-Client

# 7. WireGuard tunnel
**💪 Challenge 2:** Set up a WireGuard tunnel to the teacher’s router. A wg-quick configuration file is provided. Extract the interface address, peer public key, endpoint, listen port, and allowed-IPs from this file and configure the tunnel accordingly. The challenge is complete once the teacher’s router responds to ping over the WireGuard link.

![WG-Quick](media/WireGuardWGQuick.png)
<br>*Figure 3: DHCP client successfully acquired an IP address from the teacher’s router.*

<details>
<summary>Hint 1</summary>
<p>1. Create new WireGuard interface<br/>
2. Create new peer configuration<br/>
3. Add IP adresss<br/>
4. Ping the teachers router over the WireGuard tunnel</p>
</details>
<br/>

<details>
<summary>Hint 2</summary>
<p>Once the tunnel is configured and the connection is established, the status should look like this:</p>
<img src="./media/WireGuardHint2.png">
</details>
<br/>

## Additional Ressources - WireGuard

# 8. EoIP Tunnel
 Once the WireGuard tunnel is established, both peers have fixed IP addresses within the tunnel and can reach another. This is the requirement to build the EoIP tunnel. 

**💪 Challenge 3:** Configure the EoIP tunnel. This challenge is completed once a IP address was successfully aquired using DHCP over the EoIP tunnel. 

**Important:** Prevent the dhcp client to add a default route or set dns to prevent conflicting configuration. 

## Additional Ressources - EoIP


# 9. Capture The 🏴‍☠️ 

**💪 Challenge 4:** Access the Webbrowser in the remote network using your own laptop. For that a bridge must be added, the EoIP interface must be added as port. The dhcp client is not required and can be deleted or moved to the bridge to verify the configuration. The webserver is in the same subnet and has `.10` in the last octet. Use you laptop to access the webserver and capture the flag!

## Additional Ressources - Bridge


