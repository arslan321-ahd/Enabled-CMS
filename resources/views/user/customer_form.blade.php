<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Submission</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #ffffff;
        }

        .logo {
            max-height: 60px;
        }

        .card {
            border-radius: 12px;
        }

        .submit-btn {
            background-color: #0b3c89;
            border-color: #0b3c89;
            padding: 10px 40px;
            border-radius: 8px;
        }

        .submit-btn:hover {
            background-color: #092f6b;
        }

        .success-icon {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            border: 4px solid #4CAF50;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }

        .success-icon i {
            font-size: 42px;
            color: #4CAF50;
        }

        .emoji {
            font-size: 34px;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .emoji:hover {
            transform: scale(1.2);
        }
    </style>
</head>
<body>

<div class="container py-5">

    <!-- LOGO -->
    <div class="text-center mb-4">
        <img src="{{ asset('assets/images/altitude.png') }}" alt="Logo" class="logo">
    </div>

    <!-- FORM CARD -->
    <div class="row justify-content-center" id="formSection">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body p-4">

                    <form id="customerForm">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">Full Name *</label>
                                <input type="text" class="form-control" placeholder="Enter Full Name" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Contact Number *</label>
                                <input type="tel" class="form-control" placeholder="Enter Contact Number" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">E-mail Address *</label>
                                <input type="email" class="form-control" placeholder="Enter E-mail Address" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">City / Province (Optional)</label>
                                <input type="text" class="form-control" placeholder="Enter City / Province">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Order *</label>
                                <input type="text" class="form-control" placeholder="Enter Order" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Total Amount *</label>
                                <input type="number" class="form-control" placeholder="Enter Total Amount" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Brand *</label>
                                <select class="form-select" required>
                                    <option value="" disabled selected>Select</option>
                                    <option>Eco Flow</option>
                                    <option>Amazon</option>
                                    <option>Lazada</option>
                                    <option>Boox</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Use Case?</label>
                                <select class="form-select">
                                    <option value="" disabled selected>Select</option>
                                    <option>Digital Reading</option>
                                    <option>Note- Taking</option>
                                    <option>Productivity</option>
                                    <option>Gifting</option>
                                    <option>Other</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Order Type *</label>
                                <select class="form-select" required>
                                    <option value="" disabled selected>Select</option>
                                    <option>Order Now</option>
                                    <option>Reserve a Unit</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Where did you discover us?</label>
                                <select class="form-select">
                                    <option value="" disabled selected>Select</option>
                                    <option>Facebook</option>
                                    <option>Instagram</option>
                                    <option>Google</option>
                                    <option>Referral</option>
                                    <option>Other</option>
                                </select>
                            </div>
                            <div class="mt-4">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="emailCampaign">
                                    <label class="form-check-label" for="emailCampaign">
                                        I agree to the collection and use of my personal information as required under the Data Pricacy Act of 2012.
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="dataConsent" required>
                                    <label class="form-check-label" for="dataConsent">
                                        I consent to receive marketing compaigns and promotional materials via email.
                                    </label>
                                </div>
                            </div>

                        </div>
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary submit-btn">
                                Submit
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- SUCCESS SECTION -->
    <div class="row justify-content-center d-none" id="successSection">
        <div class="col-lg-6 text-center">

            <div class="success-icon mb-3">
                <i class="bi bi-check-lg"></i>
            </div>

            <h4 class="text-success mb-2">Form submitted successfully!</h4>
            <p class="text-muted mb-4">How was your experience?</p>

            <div class="d-flex justify-content-center gap-3">
                <span class="emoji">😡</span>
                <span class="emoji">😕</span>
                <span class="emoji">😐</span>
                <span class="emoji">🙂</span>
                <span class="emoji">😄</span>
            </div>

        </div>
    </div>

</div>

<script>
    document.getElementById('customerForm').addEventListener('submit', function (e) {
        e.preventDefault();
        document.getElementById('formSection').classList.add('d-none');
        document.getElementById('successSection').classList.remove('d-none');
    });
</script>

</body>
</html>
