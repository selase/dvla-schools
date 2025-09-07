Study the information below and let's craft a comprehensive plat for buildig the software from end to end. You will flsh out the project, craft the schema and models, work of caches and queues, controllers and services ( such as payments), select the needed packages, write the code the the best laravel php standards, and craft core unit tests using pest php.

# DVLA Project

The Ghana DVLA ( Driver and Vehicle Licensing Authority ) issues drivers licenses. They require applicatants to go through a driving school and only after they are trained by certified instructors from the driving schools, can they book for a slot to take the exams that qualifies them to get a licence. The DVLA has its own software. The goal of this project is to build a tool for the driving schools that helps them

1. Manage their operations and students and
2. interface with the DVLA such that they build trust.

The DVLA is worried people who present themselves for exams may not have been adequately trained, instructors feel they are not been paid properly because it seems seems students are not showing up at the driving school, slots are issues and bought indescrimately and yet the schools are loosing income.

The application seeks to manage all the processes that students and schools need to go through before presenting a student for the final practical and theory exams.

Certified schools would be onboarded, students/applcants can apply online to their school of choice, they will take the necessary courses, write course level quizes or tests, attend classes and marked, instructors must be present for the courses, geo and timestamped photos of both the instructor and students will be requires as proof. Any other ways of verifying attendance can be incorporated. Slots can be bought and these lots must be linked to students. Payments will happen on the platform. The application will be a single database multi tenant. Regulatory bodies such as the DVLA or Ghana Drive (the schools' Union) would have oversight and therefore may not necessary be tied to a tenant.

We would from a configuration file determine what the payment solution should be. So they may be multiple payment solutions implemented

## New Achitecure

### Process Flow

-   Student visits dvschooladmin.org
-   select a school of interest and apply to the school -
    Ghana Card or Passport ID card verification
-   School accepts application
-   Pay for tuition online and get a receipt
-   You are issued a student ID
-   Learners license @ dvla.gov.gh. The learners licence are issued by the DVLA and therefore the process is not in the scope of this application. Only the payments might

    -   Payment
    -   Eye test
    -   Biometrics

-   Use learners licence & (proof of tuition) to apply for a slot and examination slot.

    -   learners licence
    -   proof of tuitions (Fees settled) using the baseline fee approved
    -   attendance

-   After the slot is secured, student goes to the DVLA to take an exam Practicals and Theory

-   If student is successful, student is issued a drivers license by the DVLA.

    Ultimately we want the ensure the following

    -   only students coming from driving schools can present themselves to take the exams
    -   Only students who have satisfied all the academic requirements ie. taking all the prescribed courses can have a slot to take the exams.
    -   the slots are applied for by the driving school on behalf of the students.
    -   only driving schools should have access to the page to purchase slots
    -   slots can be purchased in bulk for a group of students or for individual students. To apply for slots, students who qualify must have a lerners licence, proof that tuition fees have been settled, and a proof of attendance during the lessons
    -   The verification for slots can happen automatically once the requirements are met. In addition to this, Officers from the regulatory bodies such as DVLA or Ghana Drive may have to autorize the slot purchage and approve by inspecting the submitted evidence. This may be in a configuration so that if it should be authorized, then it happens otherwise it is automatically verified.

### Requirements

-   All schools must have their gps location data available
-   Attendance sheets can be signed online
    -   sign in, select the course
    -   take a live photo of your face. The photo needs to be goetagged. This is to help us determine if this photo was taken in the school and on that date
-   Authorization will be handled by spatie
-   Use the latest version of laravel
-   Use all the necessary spatie packages
-   Use tailwind CSS
-   Use htmx where necessary

### Users

-   driving school owner
-   Driving school admin
-   Instructors
    -   Belong to driving schools
    -   Can be assigned students
    -   instructors may take theory or practical classes or both
    -   instructors must be present during classes
    -   instructors may also register with other schools. So that in case there is shortage in one school and instructor may comee in and do locum.
-   Students
    -   Students apply to a school online
    -   the school accepts the application
    -   the student registers by filling forms. since it's a driving school academic qualification may just be a drop down. schools attended and year may not be needed.
    -   Students are registered under a driving school i.e. students belong to a driving school
    -   students can pay for services such as tuition feed and learners license, etc
    -   we should be able to tell when a student has paid for a service
    -   every course has an instructor, multiple instructors can be assigned multiple courses
-   Regulatory body ( like DVLA & Ghana Drive)
-   Superadmin (owners of the application)

### Models

-   Courses ( the DVLA has a set of theory and practical lessons that students are expected to take)
    -   Courses can be taken by students
    -   studens may take a quiz or exam on courses they have just taken and have their scores uploaded as part of cumulative assessment.
-   Services(like products among others should have all the features of a product)
-   School
    -   a school has an owner and administrator
    -   the owner and administrator may be one and the same person.
    -   the admin can setup instructors, add students, view payment history, initiate payment prompts etc.)
    -   Check the students registration progress
    -   Find and edit student records
-   Payments - Payments are centralized on the platform. - Schools provide their bank account or mobile money details during setup. Settlements when a student pays will be routed to the schools account - payments will be accompanies with a service_id. - Students may pay in
    DVLA Schools — Technical Implementation Plan (Pre-Code)

        This document is a concise, AI-ready blueprint. It’s organized, unambiguous, and uses consistent semantics so an AI can reliably generate code, tests, and docs from it.

0.  Guiding Principles

    Security-first: PII, payments, attendance evidence.

    Tenant isolation: row-level scoping + Spatie Permission teams.

    Regulator visibility: cross-tenant read without data contamination.

    Deterministic workflows: state machines for compliance flows (tuition → attendance → slot).

    Idempotency: payments & slot requests.

    Async: heavy/slow tasks (image EXIF, liveness, settlements).

    Strong tests: Pest (unit, feature, API, policy, E2E happy paths).

1.  Architecture & Infrastructure
    1.1 Stack

        Backend: Laravel 12 (PHP 8.3/8.4), Redis, MySQL 8.0+

        Frontend: Blade + Tailwind CSS; HTMX for partials; Alpine.js minimal

        Auth: Laravel Fortify/Breeze; Spatie Permission (teams enabled)

        Queues / Jobs: Redis; Laravel Horizon for monitoring

        Storage: S3 (images, receipts); local in dev

        Cache: Redis (tenant-prefixed keys)

        Observability: Laravel Telescope (dev/stage), Spatie activity logs

        Docs: OpenAPI via l5-swagger

        CI/CD: GitHub Actions → Forge/Envoy; zero-downtime deploys

        Environments: dev (local), staging (seeded demo), prod (real)

1.2 Multi-Tenancy Model (Single DB)

    Tenant key: school_id on tenant-scoped tables

    Global scope: TenantScope + BelongsToTenant trait

    Resolver middleware: subdomain or header/session sets active school

    Spatie teams: teams = school_id; roles are per-school; regulators/superadmin use no team or a special global team

    Data access: All repository queries require tenant context (explicit or via scope)

1.3 Roles & Policies

    Roles: superadmin, regulator, school_owner, school_admin, instructor, student

    Policies: tenant read/write; regulator global read

    Critical actions: slot approval, refunds → policies + business rules

2.  Domain Model (ERD Outline & State Machines)
    2.1 Core Entities (high-level)

        School (tenant): profile, GPS, settlement account(s), contact

        User: base auth identity

        Student (belongs to School): PII, Ghana Card/Passport artifacts, status

        Instructor (can belong to multiple schools): profile, locum flag

        Course: theory/practical, DVLA-approved catalog; assignable to schools

        Section/Lesson (optional): within courses

        Enrollment (student↔course): progress, cumulative score

        Assessment: quiz/test attempts by course/lesson

        Attendance: student & instructor presence logs; image, EXIF GPS, timestamp, liveness verdict

        Service: “products” (tuition, learner’s license fee proxy, slot fee, exam fee)

        Payment: centralized; polymorphic to service; driver, ref, status, receipt

        Slot: exam slot request (per student or batch); status machine + audit

        RegulatoryAction: approvals/denials; notes; actor

        Evidence (files): photos, receipts, documents (S3)

        Config (per school): toggles (manual/auto approvals), thresholds (attendance %)

2.2 Key State Machines

Student Readiness
new → registered → tuition_paid → coursework_in_progress → coursework_completed → attendance_validated → eligible_for_slot

Slot Lifecycle
draft → requested → verifying (async gates) → approved | rejected → used
Gates = { learner_license_valid?, tuition_paid?, attendance_threshold_met? }

Payment
initiated → pending → succeeded | failed | refunded

3. Data Design (Tables & Indexing — concise)

    Full migrations later. Lock essentials + indexes now.

schools: id, name, slug, gps_lat, gps_lng, owner_user_id, settlement_meta(json), config(json), timestamps
IDX: slug UNIQUE, owner_user_id

users: (Laravel defaults) + phone, national_id, avatar_path, timestamps

students: id, user_id, school_id, ghana_card_no, passport_no, status, tuition_paid_at,
learner_license_no, learner_license_verified_at, timestamps
IDX: school_id, user_id, status, learner_license_no

instructors: id, user_id, primary_school_id, is_locum, timestamps
instructor_school (pivot): instructor_id, school_id

courses: id, code, title, type ENUM(theory, practical), dvla_required BOOL, meta(json), timestamps
IDX: code UNIQUE, type
school_course (pivot): school_id, course_id

enrollments: id, school_id, student_id, course_id, progress_pct, cum_score, completed_at, timestamps
IDX: school_id, student_id, course_id

assessments: id, enrollment_id, max_score, score, type, meta(json), timestamps

attendance_logs: id, school_id, student_id, instructor_id, course_id,
photo_path, lat, lng, captured_at(UTC), exif(json),
liveness_result ENUM, distance_m_from_school,
verdict ENUM(valid, suspicious, invalid), timestamps
IDX: school_id, course_id, captured_at, student_id, instructor_id
SPATIAL: composite (lat, lng) if MySQL 8 spatial

services: id, school_id NULLABLE(for global), name, code, baseline_fee, active, meta(json), timestamps
IDX: school_id, code

payments: id, school_id, student_id, service_id, driver, amount, currency, status,
tx_ref UNIQUE, provider_ref, paid_at, payload(json), timestamps
IDX: school_id, student_id, service_id, status, paid_at

slots: id, school_id, student_id, batch_id NULL, status,
approved_by NULL, approved_at NULL, rejection_reason NULL,
token UNIQUE, meta(json), timestamps
IDX: school_id, status, student_id

slot_batches: id, school_id, requested_by, status, count, timestamps

regulatory_actions: id, actor_user_id, scope ENUM(slot, student, payment),
record_id, action, notes, timestamps

evidences: id, school_id, entity_type, entity_id, type, path, meta(json), timestamps

activity_log (Spatie): standard
configs: global feature flags (e.g., manual regulator approval, enabled payment drivers)

4.  Payment Abstraction (Drivers & Flows)
    4.1 Strategy/Contract

        PaymentGatewayContract: initiate(), verify(), refund()

        Drivers: MtnMomoGateway, VodafoneCashGateway, PaystackGateway, StripeGateway, MockGateway

        Selection: config('payments.default'); per-school override via schools.config

4.2 Idempotency & Webhooks

    Idempotency: tx_ref generated server-side; DB unique constraint

    Webhooks: per driver, signature validation; enqueue VerifyPaymentJob

    Reconciliation: nightly job compares payments.status with provider API

4.3 Settlement

    Modes: Instant (e.g., Paystack split) vs T+1 batch settlement job

    Ledger: optional enhancement for auditability

5.  Attendance Verification (Trust & Evidence)
    5.1 Capture UX (HTMX)

        Student & Instructor check-in forms

        Live selfie capture (getUserMedia) → upload to S3 (presigned URL)

        Capture device location (HTML5 Geolocation); fallback: EXIF GPS

        POST to /attendance → returns verdict snippet (HTMX swap)

5.2 Backend Verification Pipeline

ProcessAttendanceJob:

    EXIF parse (lat/lng, timestamp; Intervention Image + EXIF reader)

    Distance calc from school GPS (Haversine; configurable max radius)

    Timestamp sanity (± skew window)

    Liveness heuristic baseline (blink/random pose; future WebRTC micro-challenge)

    Verdict: valid / suspicious / invalid + distance_m_from_school

    Flag suspicious to regulator queue

Privacy note: Face matching to prior selfie is v2 optional; store minimal images + hashes with retention policy. 6) Slot Governance
6.1 Eligibility Checks (Sync Gate)

Require all:

    learner_license_verified_at != null

    tuition_paid_at != null

    attendance_pct ≥ threshold (e.g., 80%)

Approval flow:

    If manual approval ON → slots.status = verifying; enqueue ReviewSlotRequestJob

    If manual OFF → auto-approve + generate token

6.2 Bulk Requests

    Schools upload CSV / select eligible students → create slot_batches + child slots

    Each slot evaluated independently; batch status reflects aggregate progress

6.3 Tokenization

    On approve: generate nonce token (UUID v7) bound to student; one-time use

7. API Design & Frontend Integration
   7.1 API (Sanctum)

/api/schools/_, /api/students/_, /api/courses/_, /api/enrollments/_, /api/attendance/_
/api/payments/initiate, /api/payments/webhook/_, /api/payments/verify
/api/slots/_, /api/slot-batches/_
/api/regulator/\* (read-only across schools; action endpoints for approvals)

7.2 Frontend (Blade + HTMX)

HTMX zones:

    Student application acceptance panel

    Payment initiation + inline verification result

    Attendance check-in verdict card (no full page reload)

    Slot “eligibility” preview on student profile

Progressive enhancement: all endpoints also return JSON when Accept: application/json. 8) Security, Compliance & Privacy

    PII at rest: S3 bucket private; presigned URLs; minimal DB fields

    Transport: HTTPS everywhere

    Authorization: Spatie roles (teams), model policies

    Audit: activity logs for sensitive actions (create, update, approve, refund)

    Secrets: .env only; no secrets in code; prefer parameter store in prod

    Rate limiting: login, payment init, slot requests

    Data retention:

        Attendance photos: N days (default 365) → purge via S3 lifecycle

        Webhook payloads: 90 days

    Backups: daily DB (encrypted) + S3 versioning; routine restore drills

9.  Performance & Scalability

    Indexes: all FKs + high-cardinality lookups (status, dates)

    N+1 avoidance: query scopes & repository patterns

    Caching:

         Course catalog; services per school (bust on update)

         Slot eligibility pre-computation (per student)

         Config cache with tenant prefix tenant:{school_id}:...

    Queues:

         Image processing, liveness heuristics

         Payment verification & reconciliation

         Batch slot evaluation

         Notifications (SMS/Email)

10. Testing Strategy (Pest)
    10.1 Layers

        Unit: services (payments, eligibility), helpers (distance, EXIF parse)

        Feature: controllers (authz, 200/403), HTMX fragments, signed webhooks

        Policy: each model’s read/write across roles & teams

        Flows (E2E light):

            Student registration → tuition pay → valid attendance → slot request → approval → token issued

            Negative paths: no tuition / bad attendance → 422/403

        Factories & Seeders: realistic data for courses, services, schools

        Fakes: payment drivers (deterministic), S3 storage fakes

        Snapshots: stable HTMX fragments (Blade snapshots)

10.2 Coverage Targets

    Core domains ≥ 85%

    Payment & slot state machines ≥ 95% branch coverage

11. DevEx & Tooling

    Makefile / Composer scripts: make setup, make qa (phpstan, pint), make test, make seed

    Static analysis: PHPStan level 8, Larastan

    Style: Pint strict + pre-commit hook

    Migrations discipline: squash draft migrations before first release

12. Phased Delivery Plan (Milestones & DoD)

    M1 — Project Bootstrap
    Laravel 12 app; core packages; teams enabled; TenantScope; base roles
    DoD: sign-in; superadmin creates school; owners invited

    M2 — Catalogs & Enrollments
    Courses (seed DVLA defaults), Services (tuition baseline); student application → acceptance → enrollment skeleton
    DoD: student can enroll; owner/admin sees roster

    M3 — Payments
    Payment contract + MockGateway + Paystack driver; webhooks + verification + receipts
    DoD: tuition payment verified; tuition flag set; receipt downloadable

    M4 — Attendance
    HTMX check-in UI, image upload, GPS capture; async pipeline (EXIF, distance, verdict)
    DoD: valid attendance recognized; suspicious flagged; regulator inbox alerted

    M5 — Readiness & Slots
    Eligibility computation; slot state machine; batch requests; approval (manual/auto)
    DoD: eligible student → approved slot token; batch happy path; audit trail

    M6 — Regulator Portal
    Cross-tenant dashboards (read-only), approvals/denials with notes
    DoD: regulator actions logged and enforced

    M7 — Reporting & Exports
    Attendance compliance, payments summary, slot utilization; CSV/XLSX exports
    DoD: school owner downloads period reports; regulator gets cross-school aggregates

    M8 — Hardening & Launch
    Pen-test fixes; performance review; backup/restore test
    DoD: green CI, ≥85% coverage, observability, runbook written

13. Config & Feature Flags

    config/payments.php: default driver; per-school overrides

    config/slots.php: manual_approval toggle; attendance threshold; radius

    config/attendance.php: require_exif?, liveness_mode (off|basic)

    Use Laravel features() helper or spatie/laravel-settings for runtime toggles

14. External Integrations (Stubs Now, Swap Later)

    Identity: Ghana Card / Passport via IdentityVerifierContract (stub driver)

    SMS: Hubtel (Notification channel)

    Email: SES/SMTP

    DVLA (future): secure APIs for slot confirmation / audit exports

15. Risk Register & Mitigations (Top 6)

    GPS spoofing / selfie fraud → EXIF + device GPS + instructor co-presence; suspicious flags; random audits

    Payment webhook abuse → signature verification; idempotency; store raw payloads; nightly reconciliation

    Tenant data leakage → team-aware RBAC; global scopes; policy tests as guards

    Image storage costs → lifecycle rules; retention caps; on-upload compression

    Queue backlog → Horizon scaling plan; per-queue separation (payments, attendance, emails)

    Manual approval bottlenecks → SLA dashboard; auto-approve rules when signals are clean

16. Developer Runbook (Daily)

php artisan migrate --seed
php artisan horizon
php artisan queue:work # local
php artisan telescope:install # dev
php artisan storage:link

    .env skeletons for drivers; rotate secrets monthly.

17. Acceptance Criteria (Functional Highlights)

    Only students with tuition paid, attendance ≥ threshold, and valid learner’s license can submit slot requests.

    Only schools can purchase/request slots; students cannot.

    Regulators can view all schools but cannot mutate tenant data outside regulated actions.

    All sensitive actions are audited (who/when/what changed).

    Payments are idempotent; refunds logged and reconciled.
