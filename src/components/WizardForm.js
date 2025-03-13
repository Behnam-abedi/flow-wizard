import React, { useState, useEffect } from 'react';
import './WizardForm.css';

const WizardForm = () => {
  const [currentStep, setCurrentStep] = useState(0);
  const [formData, setFormData] = useState({
    category: '',
    subcategory: '',
    title: '',
    firstName: '',
    lastName: '',
    email: '',
    phone: '',
    message: '',
  });
  const [isValid, setIsValid] = useState(false);

  // Define steps with their required fields for validation
  const steps = [
    { title: 'Select Category', requiredFields: ['category'] },
    { title: 'Select Subcategory', requiredFields: ['subcategory'] },
    { title: 'Enter Details', requiredFields: ['title', 'firstName', 'lastName', 'email', 'phone'] },
    { title: 'Additional Information', requiredFields: ['message'] },
    { title: 'Confirm Submission', requiredFields: [] }
  ];

  // Available categories and subcategories
  const categories = ['Coffee Beans', 'Equipment', 'Accessories'];
  const subcategories = {
    'Coffee Beans': ['Arabica', 'Robusta', 'Blend'],
    'Equipment': ['Espresso Machine', 'Coffee Grinder', 'French Press'],
    'Accessories': ['Mugs', 'Filters', 'Storage Containers']
  };

  // Validate current step
  useEffect(() => {
    const currentRequiredFields = steps[currentStep].requiredFields;
    const isStepValid = currentRequiredFields.every(field => 
      formData[field] && formData[field].trim() !== ''
    );
    setIsValid(isStepValid);
  }, [formData, currentStep]);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData({
      ...formData,
      [name]: value
    });
  };

  const handleCategoryChange = (e) => {
    const { value } = e.target;
    setFormData({
      ...formData,
      category: value,
      subcategory: '' // Reset subcategory when category changes
    });
  };

  const nextStep = () => {
    if (currentStep < steps.length - 1) {
      setCurrentStep(currentStep + 1);
    }
  };

  const prevStep = () => {
    if (currentStep > 0) {
      setCurrentStep(currentStep - 1);
    }
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    if (currentStep === steps.length - 1) {
      // Process form submission here
      console.log('Form submitted:', formData);
      alert('Form submitted successfully!');
      
      // Reset form
      setFormData({
        category: '',
        subcategory: '',
        title: '',
        firstName: '',
        lastName: '',
        email: '',
        phone: '',
        message: '',
      });
      setCurrentStep(0);
    } else {
      nextStep();
    }
  };

  return (
    <div className="wizard-form-container">
      <div className="wizard-header">
        <h2>Request Form</h2>
        <div className="steps-indicator">
          {steps.map((step, index) => (
            <div 
              key={index} 
              className={`step ${index === currentStep ? 'active' : ''} ${index < currentStep ? 'completed' : ''}`}
            >
              <div className="step-number">{index + 1}</div>
              <div className="step-title">{step.title}</div>
            </div>
          ))}
        </div>
      </div>

      <form onSubmit={handleSubmit}>
        <div className="wizard-content">
          {/* Step 1: Category Selection */}
          {currentStep === 0 && (
            <div className="form-step">
              <h3>{steps[0].title}</h3>
              <div className="form-group">
                <label htmlFor="category">Category:</label>
                <select
                  id="category"
                  name="category"
                  value={formData.category}
                  onChange={handleCategoryChange}
                  required
                >
                  <option value="">Select a category</option>
                  {categories.map((cat, index) => (
                    <option key={index} value={cat}>{cat}</option>
                  ))}
                </select>
              </div>
            </div>
          )}

          {/* Step 2: Subcategory Selection */}
          {currentStep === 1 && (
            <div className="form-step">
              <h3>{steps[1].title}</h3>
              <div className="form-group">
                <label htmlFor="subcategory">Subcategory:</label>
                <select
                  id="subcategory"
                  name="subcategory"
                  value={formData.subcategory}
                  onChange={handleChange}
                  required
                  disabled={!formData.category}
                >
                  <option value="">Select a subcategory</option>
                  {formData.category && subcategories[formData.category].map((subcat, index) => (
                    <option key={index} value={subcat}>{subcat}</option>
                  ))}
                </select>
              </div>
            </div>
          )}

          {/* Step 3: Personal Details */}
          {currentStep === 2 && (
            <div className="form-step">
              <h3>{steps[2].title}</h3>
              <div className="form-group">
                <label htmlFor="title">Title:</label>
                <select
                  id="title"
                  name="title"
                  value={formData.title}
                  onChange={handleChange}
                  required
                >
                  <option value="">Select</option>
                  <option value="Mr">Mr</option>
                  <option value="Mrs">Mrs</option>
                  <option value="Ms">Ms</option>
                  <option value="Dr">Dr</option>
                </select>
              </div>
              <div className="form-row">
                <div className="form-group">
                  <label htmlFor="firstName">First Name:</label>
                  <input
                    type="text"
                    id="firstName"
                    name="firstName"
                    value={formData.firstName}
                    onChange={handleChange}
                    required
                  />
                </div>
                <div className="form-group">
                  <label htmlFor="lastName">Last Name:</label>
                  <input
                    type="text"
                    id="lastName"
                    name="lastName"
                    value={formData.lastName}
                    onChange={handleChange}
                    required
                  />
                </div>
              </div>
              <div className="form-group">
                <label htmlFor="email">Email:</label>
                <input
                  type="email"
                  id="email"
                  name="email"
                  value={formData.email}
                  onChange={handleChange}
                  required
                />
              </div>
              <div className="form-group">
                <label htmlFor="phone">Phone:</label>
                <input
                  type="tel"
                  id="phone"
                  name="phone"
                  value={formData.phone}
                  onChange={handleChange}
                  required
                />
              </div>
            </div>
          )}

          {/* Step 4: Additional Information */}
          {currentStep === 3 && (
            <div className="form-step">
              <h3>{steps[3].title}</h3>
              <div className="form-group">
                <label htmlFor="message">Message:</label>
                <textarea
                  id="message"
                  name="message"
                  value={formData.message}
                  onChange={handleChange}
                  required
                  rows="5"
                />
              </div>
            </div>
          )}

          {/* Step 5: Confirmation */}
          {currentStep === 4 && (
            <div className="form-step confirmation-step">
              <h3>{steps[4].title}</h3>
              <div className="confirmation-details">
                <h4>Review your information:</h4>
                <p><strong>Category:</strong> {formData.category}</p>
                <p><strong>Subcategory:</strong> {formData.subcategory}</p>
                <p><strong>Name:</strong> {formData.title} {formData.firstName} {formData.lastName}</p>
                <p><strong>Email:</strong> {formData.email}</p>
                <p><strong>Phone:</strong> {formData.phone}</p>
                <p><strong>Message:</strong> {formData.message}</p>
              </div>
            </div>
          )}
        </div>

        <div className="wizard-footer">
          <button
            type="button"
            className="btn back-btn"
            onClick={prevStep}
            disabled={currentStep === 0}
          >
            Back
          </button>
          <button
            type={currentStep === steps.length - 1 ? "submit" : "button"}
            className="btn next-btn"
            onClick={currentStep === steps.length - 1 ? null : nextStep}
            disabled={!isValid}
          >
            {currentStep === steps.length - 1 ? "Submit" : "Next Step"}
          </button>
        </div>
      </form>
    </div>
  );
};

export default WizardForm; 