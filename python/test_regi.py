from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.service import Service
from webdriver_manager.chrome import ChromeDriverManager
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.common.exceptions import TimeoutException # Import this specific exception
import time

service_obj = Service(ChromeDriverManager().install())
driver = webdriver.Chrome(service=service_obj)



try:
    driver.get("http://127.0.0.1:5500/html/index.html")
    wait = WebDriverWait(driver, 10)

    # 1. Fill the form (Valid Data)
    wait.until(EC.presence_of_element_located((By.ID, "fullName"))).send_keys("john doe")
    driver.find_element(By.ID, "email").send_keys("example@gmail.com")
    driver.find_element(By.ID, "password").send_keys("Password123")
    driver.find_element(By.ID, "confirmPassword").send_keys("Password123")
    
    # 2. Click Submit
    driver.find_element(By.ID, "submitBtn").click()

    # --- NEW LOGIC STARTS HERE ---
    
    try:
        # A: Check for Success Alert FIRST
        # Wait up to 5 seconds for the alert to pop up
        WebDriverWait(driver, 5).until(EC.alert_is_present())
        
        # Switch focus to the alert to read it
        alert = driver.switch_to.alert
        alert_text = alert.text
        print(f"✅ ALERT DETECTED: {alert_text}")
        
        # Click "OK" on the alert to close it
        alert.accept() 
        print("✅ TEST PASSED: Account created successfully.")

    except TimeoutException:
        # B: If NO alert appeared after 5 seconds, check for validation errors
        print("⚠️ No success alert appeared. Checking for validation errors...")
        
        found_errors = []
        error_ids = ["nameError", "emailError", "passError", "confirmPassError"]

        for error_id in error_ids:
            # We use try/except here because sometimes elements might not even exist in the DOM
            try:
                elem = driver.find_element(By.ID, error_id)
                if elem.text != "":
                    found_errors.append(elem.text)
            except:
                pass # Element not found, skip it

        if len(found_errors) > 0:
             error_msg = "\n".join(found_errors)
             raise Exception(f"Validation Failed:\n{error_msg}")
        else:
             raise Exception("Test Failed: No alert appeared AND no error text found. Unknown state.")

except Exception as e:
    print(f"❌ TEST FAILED: {e}")

finally:
    driver.quit()