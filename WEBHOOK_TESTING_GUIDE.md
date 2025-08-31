# Webhook Testing Guide for PayMongo Integration

This guide will help you test your online payment webhook endpoint to ensure it's working correctly.

## 🚀 Quick Start Testing

### Option 1: Use the Windows Batch File (Recommended for Windows)
1. Double-click `webhook_test.bat` in your project root
2. This will run all tests automatically using cURL
3. Check the output for any errors

### Option 2: Use the PHP Test Script
1. Open terminal/command prompt in your project root
2. Run: `php webhook_test.php`
3. This will create test orders and test the webhook

### Option 3: Manual cURL Testing
Use the commands below to test individual scenarios.

## 🔍 Test Scenarios

### 1. Test Webhook Endpoint Accessibility
```bash
curl -X GET "http://localhost/webhooks/paymongo/payment-verified" -v
```
**Expected**: Should return a response (even if it's an error for GET method)

### 2. Test with Invalid Data
```bash
curl -X POST "http://localhost/webhooks/paymongo/payment-verified" \
  -H "Content-Type: application/json" \
  -d '{"invalid": "data"}' \
  -v
```
**Expected**: Should return an error response (400 Bad Request)

### 3. Test with Valid Payment Success
```bash
curl -X POST "http://localhost/webhooks/paymongo/payment-verified" \
  -H "Content-Type: application/json" \
  -d '{
    "data": {
      "id": "pi_test_123456",
      "type": "payment_intent",
      "attributes": {
        "payment_intent_id": "pi_test_123456",
        "status": "succeeded",
        "amount": 15000,
        "currency": "PHP",
        "created_at": "2024-01-01T12:00:00.000Z"
      }
    }
  }' \
  -v
```
**Expected**: Should return success response and update order status

### 4. Test with Valid Payment Failure
```bash
curl -X POST "http://localhost/webhooks/paymongo/payment-verified" \
  -H "Content-Type: application/json" \
  -d '{
    "data": {
      "id": "pi_test_789012",
      "type": "payment_intent",
      "attributes": {
        "payment_intent_id": "pi_test_789012",
        "status": "failed",
        "amount": 15000,
        "currency": "PHP",
        "created_at": "2024-01-01T12:00:00.000Z"
      }
    }
  }' \
  -v
```
**Expected**: Should return success response and mark order as failed

### 5. Test Signature Validation
```bash
curl -X POST "http://localhost/webhooks/paymongo/payment-verified" \
  -H "Content-Type: application/json" \
  -H "X-PayMongo-Signature: fake_signature_here" \
  -d '{
    "data": {
      "id": "pi_test_345678",
      "type": "payment_intent",
      "attributes": {
        "payment_intent_id": "pi_test_345678",
        "status": "succeeded",
        "amount": 15000,
        "currency": "PHP",
        "created_at": "2024-01-01T12:00:00.000Z"
      }
    }
  }' \
  -v
```
**Expected**: Should process the webhook (signature validation is currently disabled)

## 📊 Monitoring and Debugging

### Check Laravel Logs
```bash
tail -f storage/logs/laravel.log
```

### Check Database Changes
Monitor these tables for changes:
- `orders` - Check status and payment_status updates
- `order_status_history` - Check for new status entries
- `payments` - Check payment status updates
- `notifications` - Check for customer notifications

### Test Endpoint Response
Your webhook should return:
- **Success**: `{"success": true}` (200 OK)
- **Error**: `{"error": "message"}` (400/404/500)

## 🛠️ Troubleshooting

### Common Issues

1. **Webhook not accessible**
   - Check if your Laravel server is running
   - Verify the URL is correct
   - Check for any middleware blocking the route

2. **Webhook returns 404**
   - Verify the route is defined in `routes/web.php`
   - Check if the URL matches exactly
   - Ensure no authentication middleware is blocking it

3. **Webhook returns 500 error**
   - Check Laravel logs for detailed error messages
   - Verify database connections
   - Check if required models exist

4. **Order not updating**
   - Verify the `payment_intent_id` matches an existing order
   - Check if the order status is `pending_payment`
   - Ensure database transactions are working

### Debug Steps

1. **Enable detailed logging** in your webhook controller
2. **Test with simple payloads** first
3. **Verify database state** before and after webhook calls
4. **Check for any exceptions** in the Laravel logs

## 🔧 Configuration

### Environment Variables
Ensure these are set in your `.env` file:
```env
PAYMONGO_SECRET_KEY=your_secret_key
PAYMONGO_PUBLIC_KEY=your_public_key
PAYMONGO_WEBHOOK_SECRET=your_webhook_secret
```

### Webhook URL
Your webhook endpoint is:
```
POST /webhooks/paymongo/payment-verified
```

## 📱 Testing with Real PayMongo

1. **Set up webhook in PayMongo dashboard**
2. **Use ngrok or similar** to expose localhost to the internet
3. **Configure webhook URL** in PayMongo to point to your endpoint
4. **Make test payments** to trigger real webhooks

## 🧪 Advanced Testing

### Load Testing
```bash
# Test with multiple concurrent requests
for i in {1..10}; do
  curl -X POST "http://localhost/webhooks/paymongo/payment-verified" \
    -H "Content-Type: application/json" \
    -d "{\"data\": {\"id\": \"pi_test_$i\", \"attributes\": {\"payment_intent_id\": \"pi_test_$i\", \"status\": \"succeeded\"}}}" &
done
wait
```

### Error Simulation
Test various error conditions:
- Invalid JSON payloads
- Missing required fields
- Database connection issues
- Invalid payment intent IDs

## 📋 Test Checklist

- [ ] Webhook endpoint is accessible
- [ ] Invalid data is handled gracefully
- [ ] Valid success data updates order status
- [ ] Valid failure data marks order as failed
- [ ] Signature validation works (when implemented)
- [ ] Database transactions are atomic
- [ ] Error logging is comprehensive
- [ ] Notifications are sent to customers
- [ ] Order status history is updated

## 🆘 Getting Help

If you encounter issues:
1. Check the Laravel logs first
2. Verify database state
3. Test with the provided test scripts
4. Check the webhook controller code for any exceptions

## 🔄 Continuous Testing

Consider setting up automated tests:
1. **Unit tests** for webhook logic
2. **Integration tests** with test database
3. **Webhook simulation** in your CI/CD pipeline
4. **Monitoring** for webhook failures in production
