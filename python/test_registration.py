import time
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.service import Service
from webdriver_manager.chrome import ChromeDriverManager

# 1. Setup the Browser (The Puppet)
options = webdriver.ChromeOptions()
options.add_experimental_option("detach", True) # Keep browser open after test
driver = webdriver.Chrome(service=Service(ChromeDriverManager().install()), options=options)

# 2. Open the Website
# NOTE: Make sure your Live Server is running!
url = "http://127.0.0.1:5500/html/index.html" 
driver.get(url)

print("🚀 Starting Test: TC_REGISTER_001 (Happy Path)")

# 3. Find Elements and Type Data
# We look for the HTML 'id' you saw in index.html
driver.find_element(By.ID, "fullName").send_keys("Automation Bot")
driver.find_element(By.ID, "email").send_keys("bot@test.com")
driver.find_element(By.ID, "password").send_keys("Password123")
driver.find_element(By.ID, "confirmPassword").send_keys("hgf")

# 4. Click the Button
submit_btn = driver.find_element(By.ID, "submitBtn")
submit_btn.click()

# 5. Verify the Result (The Assertion)
# We wait a second for the fake "loading" to finish
time.sleep(2) 

try:
    # Switch to the popup alert to read the text
    alert = driver.switch_to.alert
    alert_text = alert.text
    
    if "SUCCESS" in alert_text:
        print("✅ TEST PASSED: Success message appeared.")
        alert.accept() # Click "OK" on the alert

    else:
        print(f"❌ TEST FAILED: Unexpected message -> {alert_text}")

except:
    print("❌ TEST FAILED: No success alert appeared!")

# Close the browser
driver.quit()