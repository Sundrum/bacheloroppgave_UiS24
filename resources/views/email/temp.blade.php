<pre>array(2) {
    [0]=>
    object(Illuminate\Database\Eloquent\Collection)#823 (2) {
      ["items":protected]=>
      array(1) {
        [0]=>
        object(App\Models\Subscription)#822 (30) {
          ["connection":protected]=>
          string(5) "pgsql"
          ["table":protected]=>
          string(13) "subscriptions"
          ["primaryKey":protected]=>
          string(15) "subscription_id"
          ["keyType":protected]=>
          string(3) "int"
          ["incrementing"]=>
          bool(false)
          ["with":protected]=>
          array(0) {
          }
          ["withCount":protected]=>
          array(0) {
          }
          ["preventsLazyLoading"]=>
          bool(false)
          ["perPage":protected]=>
          int(15)
          ["exists"]=>
          bool(true)
          ["wasRecentlyCreated"]=>
          bool(false)
          ["escapeWhenCastingToString":protected]=>
          bool(false)
          ["attributes":protected]=>
          array(17) {
            ["subscription_id"]=>
            string(32) "c02aba817f274c2b92578f848035f2de"
            ["customer_id_ref"]=>
            int(285)
            ["interval"]=>
            string(10) "8765:48:46"
            ["serialnumber"]=>
            string(7) "sigurd3"
            ["subscription_status"]=>
            int(2)
            ["created_at"]=>
            string(22) "2024-04-15 11:20:11+00"
            ["updated_at"]=>
            string(22) "2024-04-15 11:20:44+00"
            ["next_payment"]=>
            string(10) "2024-04-15"
            ["productnumber"]=>
            string(10) "21-1020-AC"
            ["product_name"]=>
            string(16) "Irrigation gen 3"
            ["product_description"]=>
            string(32) "Irrigation Gen 3, Nordic nrf9160"
            ["product_type"]=>
            int(12)
            ["product_id"]=>
            int(135)
            ["product_image_url"]=>
            string(62) "https://storage.portal.7sense.no/images/irrigation_sensor.jpeg"
            ["document_id_ref"]=>
            int(1)
            ["product_price"]=>
            string(5) "15000"
            ["subscription_price"]=>
            string(4) "1500"
          }
          ["original":protected]=>
          array(17) {
            ["subscription_id"]=>
            string(32) "c02aba817f274c2b92578f848035f2de"
            ["customer_id_ref"]=>
            int(285)
            ["interval"]=>
            string(10) "8765:48:46"
            ["serialnumber"]=>
            string(7) "sigurd3"
            ["subscription_status"]=>
            int(2)
            ["created_at"]=>
            string(22) "2024-04-15 11:20:11+00"
            ["updated_at"]=>
            string(22) "2024-04-15 11:20:44+00"
            ["next_payment"]=>
            string(10) "2024-04-15"
            ["productnumber"]=>
            string(10) "21-1020-AC"
            ["product_name"]=>
            string(16) "Irrigation gen 3"
            ["product_description"]=>
            string(32) "Irrigation Gen 3, Nordic nrf9160"
            ["product_type"]=>
            int(12)
            ["product_id"]=>
            int(135)
            ["product_image_url"]=>
            string(62) "https://storage.portal.7sense.no/images/irrigation_sensor.jpeg"
            ["document_id_ref"]=>
            int(1)
            ["product_price"]=>
            string(5) "15000"
            ["subscription_price"]=>
            string(4) "1500"
          }
          ["changes":protected]=>
          array(0) {
          }
          ["casts":protected]=>
          array(0) {
          }
          ["classCastCache":protected]=>
          array(0) {
          }
          ["attributeCastCache":protected]=>
          array(0) {
          }
          ["dates":protected]=>
          array(0) {
          }
          ["dateFormat":protected]=>
          NULL
          ["appends":protected]=>
          array(0) {
          }
          ["dispatchesEvents":protected]=>
          array(0) {
          }
          ["observables":protected]=>
          array(0) {
          }
          ["relations":protected]=>
          array(0) {
          }
          ["touches":protected]=>
          array(0) {
          }
          ["timestamps"]=>
          bool(true)
          ["hidden":protected]=>
          array(0) {
          }
          ["visible":protected]=>
          array(0) {
          }
          ["fillable":protected]=>
          array(5) {
            [0]=>
            string(8) "interval"
            [1]=>
            string(12) "serialnumber"
            [2]=>
            string(19) "subscription_status"
            [3]=>
            string(15) "customer_id_ref"
            [4]=>
            string(12) "next_payment"
          }
          ["guarded":protected]=>
          array(1) {
            [0]=>
            string(1) "*"
          }
        }
      }
      ["escapeWhenCastingToString":protected]=>
      bool(false)
    }
    [1]=>
    array(1) {
      [0]=>
      array(1) {
        [0]=>
        string(71) "Failed to charge subscription with ID: c02aba817f274c2b92578f848035f2de"
      }
    }
  }
  </pre>