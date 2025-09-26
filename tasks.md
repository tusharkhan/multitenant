Multi-Tenant Flat & Bill Management System  
Scenario  
You are tasked with building a multi-tenant system to manage buildings, flats, 
and bills. The system should support multiple house owners (each owning a 
building) and allow Admins to manage tenants across all buildings.  
Entities:  
1. Admin (Super Admin) – manages the whole system.  
2. House Owner – owns a building with multiple flats.  
Important:  
o The system must be multi-tenant, ensuring data isolation: House 
Owners can only access their own building and flats.  
User Roles & Permissions Admin (Super Admin)  
1. Can create and manage House Owners.  
2. Can create Tenants.  
3. Can view Tenant details.  
4. Can remove tenants.  
5. Can assign tenants to buildings.  
House Owner  
1. Can create Flats in their building.  
2. Can manage flat details (flat number, flat owner details).  
3. Can create Bill Categories:  
o Electricity  
o Gas bill  
o Water bill   
o Utility Charges  
4. Can create Bills for flats.  
5. Can add due amounts if a flat hasn’t paid previous bills.  
6. Receives email notifications when:  
o A new bill is created.  
o A bill is paid.  
Functional Requirements 
1. Multi-Tenant Isolation  
o House Owners cannot see other owners’ buildings, flats, tenants, 
or bills.  
2. Flats Management  
o Flats must include flat number and owner details.  
o House Owner can create, update, and delete flats.  
3. Tenant Management  
o Tenants are assigned by Admin to a building (House Owner).  
o Tenant details include name, contact, and email.  
4. Bill Management  
o Bills are assigned to flats. 
o Include: month, bill type (category), amount, status 
(paid/unpaid), and optional notes.  
o Dues management: if a bill is unpaid, the due can be carried 
forward.  
o Email notifications:  
• Bill created → notify relevant stakeholders.  
• Bill paid → notify House Owner/Admin.  
Technical Requirements  
1. Tech Stack:  
o Laravel (any recent version)  
o Frontend: Bootstrap or Tailwind CSS (UI is not the focus)  
o MySQL or PostgreSQL database  
2. Performance:  
o Queries must be optimized  
o Code must be clean, reuseable, and maintainable.  
3. Multi-Tenant Approach:  
o Subdomain or column-based tenant identification is acceptable.  
o Data isolation must be enforced at the query and middleware 
level.  
4. Email Notifications:  
o On bill creation and bill payment.  
5. Documentation:  
o Provide proper README with setup instructions.  
o Include SQL file to create the database and seed sample data.  
Deliverables  
1. Fully working Laravel project (migrations, seeders, models, controllers).  
2. SQL file for database structure and sample data.  
3. README.md including:  
o Setup instructions (local development, subdomains if used).  
o Short description of multi-tenant implementation.  
o Notes on optimization, queries, and design decisions.  