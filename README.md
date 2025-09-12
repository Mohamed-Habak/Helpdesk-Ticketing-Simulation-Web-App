# Helpdesk-Ticketing-Simulation-Web-App
A simulated Helpdesk web application designed to **demonstrate IT support workflows, role-based access, and professional UI/UX design**. Built with PHP, MySQL, and Bootstrap, this project is intended to **showcase helpdesk knowledge and process understanding** for CV or portfolio purposes.
---

## Project Structure
helpdesk/
├─ sql
│ ├─schema.sql
├─ web
│ ├─ assets/
│ │ ├─ styles.css # Custom CSS for subtle, professional visuals
│ ├─ kb/
│ │ ├─ articles.php # View knowledge base
│ │ ├─ add.php # Add new article
│ │ ├─ edit.php # Edit article
│ │ ├─ view.php # View article details
│ ├─ tickets/
│ │ ├─ list.php # Tickets list
│ │ ├─ new.php # Create new ticket
│ │ ├─ view.php # View ticket details & comments
│ ├─ config.php # Database configuration
│ ├─ dashboard.php # Overview of tickets and navigation
│ ├─ index.php / logout.php # User authentication and intro page
├─ README.md   # current md file


---

## Features & Design Decisions

### User Roles
- **User**: Can create tickets, view their own tickets, and browse FAQ & Guides.  
- **Agent**: Can view all tickets, add comments, update ticket status, and manage FAQ & Guides.  
> *Note: There is no admin role — agents act as the privileged users in this simulation.*

### Ticket Lifecycle (Simulation)
- Statuses: `Open`, `Assigned`, `Resolved`, `Closed`  
- **Purpose**: Illustrate a real helpdesk workflow.  
- **Implementation**: Currently placeholders; no backend automation exists. They **demonstrate understanding of IT processes**, not live workflow management.  

### FAQ & Guides
- Helps users **self-serve** and reduces unnecessary ticket submissions.  
- Only agents can add or edit entries.  
- Demonstrates **documentation and process improvement skills**.  

### Comments
- Simulates **user-agent communication** on tickets.  
- Includes visual enhancements: shadowed cards, subtle borders, and formatting for diagnostic reports.  

### Visual Design & UI/UX
- Professional and subtle colors: avoids generic “parrot” look (bright rainbow colors).  
- Logout button slightly highlighted to **draw attention without being garish**.  
- Consistent design language across all pages for **corporate-like feel**.  
- Focus on readability, hierarchy, and usability.  

---

## Usage

1. Import the database schema: `sql/schema.sql`.  
2. Update database credentials in `web/config.php`.  
3. Start a local server (XAMPP/WAMP/MAMP) and navigate to `web/index.php`.  
4. Log in with a **user** account to create tickets, or an **agent** account to manage them.  

### Ticket Simulation Workflow
1. **User** creates a new ticket (title, description, priority).  
2. **Agent** views ticket list, adds comments, and optionally updates status.  
3. **User** can see agent comments and continue the conversation.  
4. Repeat until ticket is **resolved** or **closed**.  
5. **Agents** can create or update FAQ & Guides entries for common issues.  

> This workflow is a simulation to show understanding of **ticket lifecycle, accountability, and communication**, even though backend automation for status updates isn’t implemented.

---

## Why This Demonstrates Helpdesk Expertise

- Shows understanding of **ticket management processes end-to-end**.  
- Demonstrates **role-based access control and security awareness**.  
- Highlights **communication and documentation practices** through comments and FAQ & Guides.  
- Reflects **professional UI/UX design decisions** suitable for corporate IT environments.  
- Exhibits **practical knowledge of workflow, accountability, and IT support processes**, not just coding.  

---

## Notes

- Statuses (`Open`, `Assigned`, `Resolved`, `Closed`) are **placeholders**, not active rules.  
- This project is a **simulation**, meant to demonstrate knowledge, not production-ready functionality.  
- All visual enhancements (colors, shadows, layout) are designed to **look professional and subtle**, rather than “dead” or overly bright.  

---

## Summary

This project is **more than code**: it demonstrates understanding of **why ticket management exists**, not just how to implement it. By completing it, I can show potential employers that I know **both the technical and procedural aspects** of helpdesk operations — critical for Helpdesk or IT support roles.
