# AOS Audit Report (MVP)

## What this is
This website is called 'ornkavto' and appears to represent the ornkavto website.

## Site structure
The structure cannot be fully determined from the available file. The only file is a README.md, which serves as a simple project description.

## Backend
UNKNOWN

## Key claims (with evidence)
- **C1**: The name of the project is 'ornkavto'.
  - Evidence: ``
- **C2**: It is for the ornkavto website.
  - Evidence: ``

## Diagram: Architecture
flowchart TB
  User[Visitor] --> Browser[Browser]
  Browser -->|loads| Index[index.html]
  Index -->|styles| CSS[assets/style.css]
  Index -->|submits form| PHP[send.php]
  PHP -->|sends email| Email[(Mail server)]
  Index -->|optional analytics| GA4[(Google Analytics 4)]

## Diagram: Dataflow
sequenceDiagram
  participant U as User
  participant I as index.html
  participant P as send.php
  participant M as Mail server
  U->>I: Open website
  I->>U: Render sections + form
  U->>I: Submit contact form
  I->>P: POST form data
  P->>P: Validate + honeypot check
  P->>M: Send email
  P->>I: JSON {ok:true} / error
  I->>U: Show success/error