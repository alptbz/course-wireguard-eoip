# Lab 1 - Capture The Flag <!-- omit in toc -->

# Table of contents <!-- omit in toc -->
- [1. Requirements](#1-requirements)
- [2. Learning objectives](#2-learning-objectives)
- [3. Remarks](#3-remarks)
- [4. Preparation](#4-preparation)
- [5. Connect to Teachers Network](#5-connect-to-teachers-network)

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

# 5. Connect to Teachers Network
Your course instructor will have a Wi-Fi network for you to connect your router. 

**💪 Challenge 1:** Connect your Router to the Wi-Fi network provieded by the course instructor. *Typically the SSID is `Capture The 🏴‍☠️`*

<details>
<summary>Solution</summary>
<pre>
/interface/wireless/security-profiles/add name=CTF authentication-types=wpa2-psk wpa2-pre-shared-key=1234
</pre>
</details>
<br/>