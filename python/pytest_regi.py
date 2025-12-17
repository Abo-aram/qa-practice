import pytest
import itertools
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.service import Service
from webdriver_manager.chrome import ChromeDriverManager
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.common.exceptions import TimeoutException
from selenium.webdriver.chrome.options import Options # <--- Add this import

# Data Setup
# Reduced lists for faster testing
fullName = ["john doe", "J"]             # 1 Good, 1 Bad
email = ["example@gmail.com", "bad-mail"] # 1 Good, 1 Bad
password = ["Password123"]                # Keep it simple
confirmPassword = ["Password123", "diff"] # 1 Good, 1 Bad

# Total tests = 2 * 2 * 1 * 2 = 8 Tests (Takes ~30 seconds)
combined_data = list(itertools.product(fullName, email, password, confirmPassword))


@pytest.fixture
def driver():
    Service_obj = Service(ChromeDriverManager().install())
    
    # --- MAKE IT INVISIBLE ---
    chrome_ops = Options()
    chrome_ops.add_argument("--headless") # Run in background
    chrome_ops.add_argument("--disable-gpu")
    
    # Pass the options to the driver
    driver = webdriver.Chrome(service=Service_obj, options=chrome_ops)
    
    yield driver
    driver.quit()

@pytest.mark.parametrize("fullName, email, password, confirmPassword", combined_data)
def test_data_combinations(driver, fullName, email, password, confirmPassword):
    driver.get("http://127.0.0.1:5500/html/index.html")
    wait = WebDriverWait(driver, 10)
    
    # Fill Form
    wait.until(EC.presence_of_element_located((By.ID, "fullName"))).send_keys(fullName)
    driver.find_element(By.ID, "email").send_keys(email)
    driver.find_element(By.ID, "password").send_keys(password)
    driver.find_element(By.ID, "confirmPassword").send_keys(confirmPassword)
    driver.find_element(By.ID, "submitBtn").click()

    # Determine Result
    try:
        WebDriverWait(driver, 2).until(EC.alert_is_present())
        alert = driver.switch_to.alert
        print("✅test passed:", alert.text)
        alert.accept()
        
        # FIX 1: Correct spelling
        result = "success" 
        
    except TimeoutException:
        # FIX 2: Correct spelling (elements)
        error_elements = driver.find_elements(By.CLASS_NAME, "error-msg")
        found_errors = []
        
        for el in error_elements:
            if el.text != "":
                found_errors.append(el.text)
        
        if found_errors:
            print("❌test failed with errors:", found_errors)
            # FIX 3: Correct spelling
            result = "failure"
        else:
            # FIX 4: Correct spelling
            result = "silent"
        
    # FIX 5: Correct spelling in assertion
    # Also fixed the variable name in the error message string to match your inputs
    # Passes ONLY if result is "success". Fails if "failure" or "silent".
    assert result == "success", f"Test Failed! Webpage showed these errors: {found_errors if 'found_errors' in locals() else 'No response'}"