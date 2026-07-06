# Data Purchase System Flow


This document outlines the end-to-end flow of the data purchase system, from user interaction to provider fulfillment and feedback.

## 1. User Interaction (The Frontend)
*   **Selection:** The user visits the website (Home or Shop), selects a data bundle (e.g., **MTN 1GB**), and enters their phone number (`024xxxxxxx`).
*   **Initiation:** The user clicks "Buy Data".
*   **API Call:** The frontend sends a request to `api/order/direct-purchase`.
    *   The system creates a **Transaction** record (`status: pending`).
    *   The system creates an **Order** record (`status: pending`, `volume: 1000` MB).
*   **Payment:** The user is redirected to the **Paystack** checkout page.

## 2. Payment & Verification (The Bridge)
*   **Payment Success:** The user completes the payment on Paystack.
*   **Redirect:** The user is redirected back to `/direct-purchase-success`.
*   **Triggering the System:**
    *   **Live Environment:** Paystack sends a background **Webhook** to your server (`api/webhook/paystack`).
    *   **Local Environment:** The success page automatically executes a verification script (`api/order/verify-payment`), verifying the transaction with Paystack API.
*   **Result:** The database updates the **Transaction** to `success` and the **Order** to `accepted`. The **`ProcessingService`** is immediately triggered.

## 3. Processing Logic (The "Brain")
Logic location: `App\Classes\ProcessingService.php`

1.  **Configuration Check:** The service looks up the **Routing Configuration** set in the Admin Panel (`configurations` table).
    *   *Example:* It checks `mtn_provider`. (e.g., set to **"ckgodsway"** or **"hubnet"**).
2.  **Mode Check:** It checks if Auto-Processing is enabled globally and for the specific network.
3.  **Provider Selection:**
    *   If `mtn_provider` is `hubnet` -> Instantiates **`App\Classes\HubnetService`**.
    *   If `mtn_provider` is `ckgodsway` -> Instantiates **`App\Classes\CKGodswayService`**.

## 4. Provider Execution (The "Action")

### Case A: CKGodsway (`App\Classes\CKGodswayService`)
1.  **Data Normalization:**
    *   Takes database volume (e.g., **1000** MB).
    *   Divides by 1000 (Result: **1** GB). *CK API expects GB.*
2.  **Network Mapping:**
    *   Maps system network `mtn` -> **`MTN_PRO`**.
    *   Maps system network `at` -> **`AT_PREMIUM`**.
    *   Maps system network `telecel` -> **`TELECEL`**.
3.  **API Request:**
    *   Sends `POST` to `https://console.ckgodsway.com/api/data-purchase`.
    *   Headers: `X-API-Key` (from Admin Settings).
    *   Payload: `{"networkKey": "MTN_PRO", "recipient": "024...", "capacity": "1"}`.

### Case B: Hubnet (`App\Classes\HubnetService`)
1.  **Data Usage:**
    *   Uses database volume as is (e.g., **1000**). *Hubnet API expects MB.*
2.  **Network Mapping:**
    *   Uses system network `mtn` (lowercase) directly.
3.  **API Request:**
    *   Sends `POST` to Hubnet endpoint.
    *   Payload: `{"network": "mtn", "volume": "1000", ...}`.

## 5. Final Feedback (The Output)
*   **Database Update:**
    *   If the API call is successful, the Order status is set to `accepted`.
    *   **Crucial:** The status message is set to **"Order sent for processing."**
    *   *Note:* The user **never** sees the provider name (CKGodsway or Hubnet). They only see the generic success message.
*   **Notification:**
    *   An admin notification is sent to **Telegram**, containing full details (Network, Provider used, Amount, Recipient).
*   **User View:**
    *   The success page reloads. The user sees "Payment Successful" and "Status: Accepted".
