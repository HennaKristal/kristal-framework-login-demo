<?php include "partials/navigation.php"; ?>

<div class="frontpage">

  <!-- Hero Section -->
  <section class="hero-section" style="background-image: url('<?php echo webp("pictures/image-1.webp"); ?>');" alt="Hero banner image">
      <div class="container">
          <div class="row align-items-center">
              <div class="col-lg-6 hero-text">
                  <h1 class="hero-title">A Simple, Secure Login Framework</h1>
                  <p class="hero-subtitle">
                    A modern example project for authentication, user sessions, and secure access control. Built to demonstrate best practices in login flows, security, and usability.
                  </p>

                  <div class="hero-actions">
                      <a href="<?php echo route("create-account"); ?>" class="btn btn-success hero-primary">
                          Get Started Free
                      </a>
                      <a href="<?php echo route("login"); ?>" class="btn btn-outline-light hero-secondary">
                          Sign In
                      </a>
                  </div>

                  <p class="hero-footnote">
                        MIT license, free for commercial use.
                  </p>
              </div>
          </div>
      </div>
  </section>

    <!-- Key Features Section -->
    <section class="features-section">
        <div class="container">
            <h2 class="section-title text-center">Everything You Need for a Login Demo</h2>
            <p class="section-subtitle text-center">
                Designed as a reference implementation for developers who value clarity, security, and clean structure.
            </p>

            <div class="row g-4 mt-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="shine-overlay"></div>
                        <img class="feature-icon" width="48" height="48" src="<?php echo webp('pictures/icon-encryption.webp'); ?>" alt="encryption icon"/>
                        <h3 class="feature-title">Secure Authentication</h3>
                        <p class="feature-text">
                            User credentials are handled securely using modern, industry standard practices. This project demonstrates safe authentication flows without exposing sensitive data.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="shine-overlay"></div>
                        <img class="feature-icon" width="48" height="48" src="<?php echo webp('pictures/icon-passwords.webp'); ?>" alt="password generator icon"/>
                        <h3 class="feature-title">Session Management</h3>
                        <p class="feature-text">
                            Shows how to create, validate, and expire user sessions reliably, including automatic lockout behavior after repeated failed attempts.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="shine-overlay"></div>
                        <img class="feature-icon" width="48" height="48" src="<?php echo webp('pictures/icon-cloud.webp'); ?>" alt="secure sync icon"/>
                        <h3 class="feature-title">Enhanced Protection</h3>
                        <p class="feature-text">
                            Allow users to enable 2 factor authentication by sending a code to email when logging in.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>


   <!-- Security Highlight Section -->
   <section class="security-section"
            style="background-image: url('<?php echo webp("pictures/image-2.webp"); ?>');">
       <div class="container">
           <div class="row align-items-center">
               <div class="col-lg-6 security-text">
                   <h2 class="section-title">Security First By Design</h2>
                   <p class="section-subtitle">Every part of this example is built with security in mind, showing how a real-world login system should be structured.</p>
                   <ul class="security-list">
                       <li>Built using modern, well established security practices.</li>
                       <li>Sensitive data is protected and never exposed unnecessarily.</li>
                       <li>Demonstrates account lockout after repeated failed login attempts.</li>
                       <li>Optional two-factor authentication flow included as an example.</li>
                   </ul>
               </div>


             

               <div class="col-lg-6 security-visual">
                    <div class="security-card">
                        <div class="security-card-header">
                            <h2 class="security-card-title">Example Features Included</h2>
                        </div>
                        <div class="security-card-body">
                            <div class="security-vault-item">
                                <div class="vault-service">User Accounts</div>
                                <div class="vault-status">Included</div>
                            </div>
                            <div class="security-vault-item">
                                <div class="vault-service">Protected Routes</div>
                                <div class="vault-status">Included</div>
                            </div>
                            <div class="security-vault-item">
                                <div class="vault-service">Session Handling</div>
                                <div class="vault-status">Included</div>
                            </div>
                            <div class="security-vault-item">
                                <div class="vault-service">Clean Architecture</div>
                                <div class="vault-status">Included</div>
                            </div>
                            <div class="security-vault-footer">
                                Modern authentication for modern applications.
                            </div>
                        </div>
                    </div>
                </div>


           </div>
       </div>
   </section>

    <!-- Cross-Platform Section -->
    <section class="platform-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-4 platform-text">
                    <h2 class="section-title">Use It Anywhere You Build</h2>
                    <p class="section-subtitle">
                        This login framework example is suitable for web apps, tools, dashboards, or prototypes. It demonstrates how authentication should feel across devices.
                    </p>
                    <ul class="platform-list">
                        <li>Desktop layout for focused workflows</li>
                        <li>Tablet friendly responsive design</li>
                        <li>Mobile ready login experience</li>
                    </ul>
                </div>
                <div class="col-lg-6 ms-auto platform-visual">
                    <img src="<?php echo webp("pictures/image-3.webp"); ?>" alt="Password manager on laptop, tablet and phone" class="img-fluid platform-image">
                </div>
            </div>
        </div>
    </section>

    <!-- Final Call To Action -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-card">
                <h2 class="cta-title">Ready to Explore the Login Flow?</h2>
                <p class="cta-text">
                    Create an account and start experimenting with a clean, secure authentication example today.
                </p>
                <a href="<?php echo route("create-account"); ?>" class="btn btn-light hero-secondary">
                    Create Free Account
                </a>
            </div>
        </div>
    </section>

</div>


<script src="<?php echo js("front-page.js"); ?>"></script>