<?php
    include '../Register/connect.php';
    include '../Register/auth.php';
    include '../sidebar.php';
    include '../nav.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Request</title>
    <!-- Tagify CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/custom.css">
    <style>
        .form-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .offer-options {
            margin-top: 20px;
        }
        .hidden-field {
            display: none;
        }
    </style>
</head>
<body>
    <div class="content-wrapper">
        <div class="container py-5">
            <div class="form-container">
                <h2 class="mb-4">Add New Request</h2>
                
                <form id="requestForm" method="POST" action="add_request_controller.php">
                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4" placeholder="Describe what you need..." required></textarea>
                    </div>
                    
                    <!-- Skills Needed (Multi-select) -->
                    <div class="mb-3">
                        <label for="skillsneeded" class="form-label">Skills Needed</label>
                        <input name="skillsneeded" id="skills-input" class="form-control" placeholder="Type to search skills...">
                    </div>
                    
                    <!-- Offer Options -->
                    <div class="offer-options">
                        <h5>What are you offering?</h5>
                        
                        <!-- Money Option -->
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="offerMoney" name="offer_type[]" value="money">
                            <label class="form-check-label" for="offerMoney">Offer Money</label>
                        </div>
                        
                        <div class="mb-3 hidden-field" id="moneyAmountField">
                            <label for="moneyAmount" class="form-label">Amount (EGP)</label>
                            <input type="number" class="form-control" id="moneyAmount" name="money_amount" placeholder="Enter amount">
                        </div>
                        
                        <!-- Skill Option -->
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="offerSkill" name="offer_type[]" value="skill">
                            <label class="form-check-label" for="offerSkill">Offer Skill</label>
                        </div>
                        
                        <div class="mb-3 hidden-field" id="skillOfferField">
                            <label for="skillOffer" class="form-label">Skill to Offer</label>
                            <input name="skillOffer" id="skillOffer-input" class="form-control" placeholder="Type to search skills...">
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 mt-3">Submit Request</button>
                </form>
            </div>
        </div>
    </div>
  
    <?php
    include('../footer.php');
    ?>
    <!-- Tagify JS -->
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  
    <!-- Custom Script -->
    <script>
        const input = document.querySelector('#skills-input');
        let tagify;

        fetch('../Register/get_skills.php')
        .then(res => res.json())
        .then(data => {
            tagify = new Tagify(input, {
                whitelist: data,
                maxTags: 10,
                dropdown: {
                    maxItems: 20,
                    classname: "tags-look",
                    enabled: 0,
                    closeOnSelect: false
                }
            });
        });
        document.getElementById('requestForm').addEventListener('submit', function () {
        input.value = JSON.stringify(tagify.value);
        });

        const skillOffer_input = document.querySelector('#skillOffer-input');
        let tagify_offer;

        fetch('../Register/get_skills.php')
        .then(res => res.json())
        .then(data => {
            tagify_offer = new Tagify(skillOffer_input, {
                whitelist: data,
                maxTags: 10,
                dropdown: {
                    maxItems: 20,
                    classname: "tags-look",
                    enabled: 0,
                    closeOnSelect: false
                }
            });
        });
        document.getElementById('requestForm').addEventListener('submit', function () {
        skillOffer_input.value = JSON.stringify(tagify_offer.value);
        });

        // Show/hide money amount field
        document.getElementById('offerMoney').addEventListener('change', function() {
            document.getElementById('moneyAmountField').style.display = 
                this.checked ? 'block' : 'none';
        });
        
        // Show/hide skill offer field
        document.getElementById('offerSkill').addEventListener('change', function() {
            document.getElementById('skillOfferField').style.display = 
                this.checked ? 'block' : 'none';
        });
    </script>
</body>
</html>
