# Pricing & Promotion Flow Diagram

## 1. Walk-in Reservation Creation Flow

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         STAFF CREATES WALK-IN RESERVATION                    │
└─────────────────────────────────────────────────────────────────────────────┘
                                      │
                                      ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│  POST /api/reservations.php?action=create_walkin                             │
│                                                                              │
│  Input: propertyId, roomId, checkInDate, checkInTime,                        │
│         bookingType, totalAmount, guest info...                              │
└─────────────────────────────────────────────────────────────────────────────┘
                                      │
                                      ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│  STEP 1: Create Reservation Record                                           │
│  ─────────────────────────────────                                           │
│  INSERT INTO reservations (reservation_id, reservation_code, ...)          │
│                                                                              │
│  Result: reservation_id = "res-abc123"                                       │
└─────────────────────────────────────────────────────────────────────────────┘
                                      │
                                      ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│  STEP 2: Create Reservation Room (if room selected)                          │
│  ─────────────────────────────────────────────                                   │
│  INSERT INTO reservation_rooms (res_room_id, reservation_id, room_id...)      │
│                                                                              │
│  Result: res_room_id = "rr-xyz789"                                           │
└─────────────────────────────────────────────────────────────────────────────┘
                                      │
                                      ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│  STEP 3: 🔥 NEW - Calculate Pricing Breakdown                                │
│  ─────────────────────────────────────────                                   │
│  CALL: savePricingBreakdown($pdo, reservation_id, res_room_id...)            │
└─────────────────────────────────────────────────────────────────────────────┘
                                      │
                                      ▼
```

## 2. Pricing Breakdown Calculation (Inside savePricingBreakdown)

```
┌─────────────────────────────────────────────────────────────────────────────┐
│              savePricingBreakdown() FUNCTION EXECUTION                       │
└─────────────────────────────────────────────────────────────────────────────┘
                                      │
                    ┌─────────────────┼─────────────────┐
                    ▼                 ▼                 ▼
           ┌────────────┐     ┌────────────┐     ┌────────────┐
           │  LAYER 1   │     │  LAYER 2   │     │  LAYER 3   │
           │  Base Rate │────▶│  Seasonal  │────▶│   Rules    │
           │            │     │   Rates    │     │            │
           └────────────┘     └────────────┘     └────────────┘
                    │                 │                 │
                    ▼                 ▼                 ▼
           ┌────────────┐     ┌────────────┐     ┌────────────┐
           │ LAYER 4    │     │            │     │            │
           │  Auto-     │     │            │     │            │
           │ Promotions │     │            │     │            │
           └────────────┘     └────────────┘     └────────────┘
```

### Detailed Calculation Steps:

```
START: base_amount = ₱5,000 (from reservation totalAmount)

LAYER 1: BASE RATE
├─ INSERT INTO reservation_pricing_breakdown
│   component_type = 'base_rate'
│   component_name = 'Base Room Rate'
│   base_amount = 5000
│   final_amount = 5000
│
│   Table Row Created:
│   ┌────────────┬──────────────┬─────────┬─────────────┐
│   │breakdown_id│ component_   │base_amt │ final_amt   │
│   │  uuid-1    │ base_rate    │ 5000.00 │  5000.00    │
│   └────────────┴──────────────┴─────────┴─────────────┘
│
└─► base_amount = 5000

LAYER 2: SEASONAL RATES CHECK
├─ SELECT FROM seasonal_rates WHERE:
│   property_id = ? AND date_start <= checkInDate <= date_end
│
├─ Found: "Summer Peak Season" (rate_multiplier = 1.2)
│   season_id = "sea-123"
│
├─ INSERT INTO reservation_pricing_breakdown
│   component_type = 'seasonal_rate'
│   component_name = 'Summer Peak Season'
│   season_id = 'sea-123' (🔗 FK LINK!)
│   base_amount = 5000
│   multiplier = 1.2
│   final_amount = 6000
│
│   Table Row Created:
│   ┌────────────┬──────────────┬─────────┬────────┬─────────────┐
│   │breakdown_id│ season_id    │mult    │base_amt│ final_amt   │
│   │  uuid-2    │ sea-123      │ 1.200  │5000.00 │  6000.00    │
│   └────────────┴──────────────┴────────┴────────┴─────────────┘
│
└─► base_amount = 6000 (carried forward)

LAYER 3: PRICING RULES CHECK
├─ SELECT FROM pricing_rules WHERE:
│   property_id = ? AND is_active = 1
│   AND (day_of_week matches checkInDate)
│   AND (date range matches)
│
├─ Found: "Weekend Surcharge" (rule_type = 'percentage_add', value = 10)
│   rule_id = "pr-456"
│
├─ INSERT INTO reservation_pricing_breakdown
│   component_type = 'pricing_rule'
│   component_name = 'Weekend Surcharge'
│   rule_id = 'pr-456' (🔗 FK LINK!)
│   base_amount = 6000
│   final_amount = 6600 (6000 + 10%)
│
│   Table Row Created:
│   ┌────────────┬──────────────┬─────────┬─────────────┬──────────┐
│   │breakdown_id│ rule_id      │base_amt │ discount_amt│final_amt │
│   │  uuid-3    │ pr-456       │6000.00  │    0.00     │6600.00   │
│   └────────────┴──────────────┴─────────┴─────────────┴──────────┘
│
└─► base_amount = 6600 (carried forward)

LAYER 4: AUTO-PROMOTIONS CHECK
├─ SELECT FROM promotions WHERE:
│   promo_type IN ('banner', 'seasonal_deal')
│   AND valid_from <= checkInDate <= valid_until
│   AND promo_code IS NULL (auto-applied)
│
├─ Found: "Summer Sale Banner" (discount = 10%)
│   promo_id = "promo-789"
│
├─ INSERT INTO reservation_pricing_breakdown
│   component_type = 'promotion'
│   component_name = 'Summer Sale Banner'
│   promo_id = 'promo-789' (🔗 FK LINK!)
│   base_amount = 6600
│   discount_amount = 660
│   final_amount = 5940
│
│   Table Row Created:
│   ┌────────────┬──────────────┬─────────┬─────────────┬──────────┐
│   │breakdown_id│ promo_id     │base_amt │ discount_amt│final_amt │
│   │  uuid-4    │ promo-789    │6600.00  │   660.00    │5940.00   │
│   └────────────┴──────────────┴─────────┴─────────────┴──────────┘
│
└─► base_amount = 5940 (FINAL)

END: Return array of 4 breakdown_ids
```

## 3. Final Pricing Breakdown Table State

```
reservation_pricing_breakdown
┌────────────────┬─────────────────┬────────────────┬─────────────┬─────────┬─────────────┬──────────┐
│ breakdown_id   │ reservation_id  │ res_room_id    │component_typ│ rule_id │ season_id   │ promo_id │
├────────────────┼─────────────────┼────────────────┼─────────────┼─────────┼─────────────┼──────────┤
│ bd-uuid-1      │ res-abc123      │ rr-xyz789      │ base_rate   │ NULL    │ NULL        │ NULL     │
│ bd-uuid-2      │ res-abc123      │ rr-xyz789      │ seasonal    │ NULL    │ sea-123     │ NULL     │
│ bd-uuid-3      │ res-abc123      │ rr-xyz789      │ pricing_rule│ pr-456  │ NULL        │ NULL     │
│ bd-uuid-4      │ res-abc123      │ rr-xyz789      │ promotion   │ NULL    │ NULL        │promo-789 │
└────────────────┴─────────────────┴────────────────┴─────────────┴─────────┴─────────────┴──────────┘

🔗 FK LINKS TO YOUR EXISTING TABLES:
   • rule_id → pricing_rules.rule_id
   • season_id → seasonal_rates.season_id  
   • promo_id → promotions.promo_id
```

## 4. Manual Promo Code Application Flow

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    STAFF APPLIES PROMO CODE MANUALLY                         │
└─────────────────────────────────────────────────────────────────────────────┘
                                      │
                                      ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│  STEP 1: Validate Promo Code                                                 │
│  ───────────────────────────                                                 │
│  GET /api/reservation_promotions.php?action=validate&code=SUMMER2024       │
│                                                                              │
│  Check:                                                                      │
│   ✓ Code exists in promotions table?                                         │
│   ✓ Is active?                                                               │
│   ✓ Valid dates (valid_from <= today <= valid_until)?                        │
│   ✓ Not already used on this reservation?                                    │
│   ✓ Get linked discount_id (if exists)                                       │
│                                                                              │
│  Result: ✅ "Summer Sale" promo found, valid                                 │
│          Linked discount: disc-456 (10% off)                                  │
└─────────────────────────────────────────────────────────────────────────────┘
                                      │
                                      ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│  STEP 2: Apply Promo to Reservation                                          │
│  ───────────────────────────────────                                           │
│  POST /api/reservation_promotions.php?action=apply                           │
│                                                                              │
│  Creates THREE records (Hybrid Architecture):                                │
│                                                                              │
│  ┌─────────────────────────────────────────────────────────────────────────┐ │
│  │ 1️⃣ BILLING LAYER: discount_usages TABLE                                  │ │
│  │    ─────────────────────────────────────                                  │ │
│  │    ┌──────────┬────────────┬───────────────┬─────────┬────────────┐     │ │
│  │    │ usage_id │discount_id │reservation_id │user_id  │amount_saved│     │ │
│  │    │ du-uuid  │ disc-456   │ res-abc123    │ staff-1 │   660.00   │     │ │
│  │    └──────────┴────────────┴───────────────┴─────────┴────────────┘     │ │
│  │    ──► Used by invoices, billing reports, accounting                     │ │
│  └─────────────────────────────────────────────────────────────────────────┘ │
│                                                                              │
│  ┌─────────────────────────────────────────────────────────────────────────┐ │
│  │ 2️⃣ MARKETING LAYER: reservation_promotions TABLE                          │ │
│  │    ────────────────────────────────────────────                          │ │
│  │    ┌─────────────┬───────────────┬─────────┬───────────┬────────────┐   │ │
│  │    │res_promo_id │reservation_id │promo_id │promo_code │discount_amt│   │ │
│  │    │ rp-uuid     │ res-abc123    │promo-789│SUMMER2024 │   660.00   │   │ │
│  │    └─────────────┴───────────────┴─────────┴───────────┴────────────┘   │ │
│  │    ──► Used for marketing ROI, campaign attribution                      │ │
│  └─────────────────────────────────────────────────────────────────────────┘ │
│                                                                              │
│  ┌─────────────────────────────────────────────────────────────────────────┐ │
│  │ 3️⃣ AUDIT LAYER: reservation_pricing_breakdown TABLE                      │ │
│  │    ────────────────────────────────────────────────                      │ │
│  │    ┌────────────┬───────────────┬──────────────┬─────────┬────────────┐  │ │
│  │    │breakdown_id│reservation_id │component_type│promo_id │discount_amt│  │ │
│  │    │ bd-uuid    │ res-abc123    │ promotion    │promo-789│   660.00   │  │ │
│  │    └────────────┴───────────────┴──────────────┴─────────┴────────────┘  │ │
│  │    ──► Complete price audit trail, links to all pricing components        │ │
│  └─────────────────────────────────────────────────────────────────────────┘ │
│                                                                              │
│  4️⃣ Updates reservations TABLE:                                             │
│  UPDATE reservations SET                                                     │
│    discount_amount = discount_amount + 660,                                  │
│    total_amount = total_amount - 660,                                        │
│    balance_amount = balance_amount - 660                                     │
│  WHERE reservation_id = 'res-abc123'                                         │
└─────────────────────────────────────────────────────────────────────────────┘
```

## 5. Viewing Reservation with New Data

```
GET /api/reservations.php?action=single&id=res-abc123

Response:
{
  "reservation_id": "res-abc123",
  "reservation_code": "RES-20240001",
  "total_amount": 5940.00,
  "discount_amount": 660.00,
  
  // 🔥 NEW: Complete price audit trail
  "pricing_breakdown": [
    {
      "component_type": "base_rate",
      "component_name": "Base Room Rate",
      "base_amount": 5000.00,
      "final_amount": 5000.00,
      // No FK links (it's the base)
    },
    {
      "component_type": "seasonal_rate",
      "component_name": "Summer Peak Season",
      "season_id": "sea-123",           // 🔗 LINK to seasonal_rates
      "season_name": "Summer Peak",      // Joined from seasonal_rates
      "multiplier": 1.2,
      "base_amount": 5000.00,
      "final_amount": 6000.00
    },
    {
      "component_type": "pricing_rule",
      "component_name": "Weekend Surcharge",
      "rule_id": "pr-456",              // 🔗 LINK to pricing_rules
      "rule_name": "Weekend Surcharge",  // Joined from pricing_rules
      "base_amount": 6000.00,
      "final_amount": 6600.00
    },
    {
      "component_type": "promotion",
      "component_name": "Summer Sale Banner",
      "promo_id": "promo-789",          // 🔗 LINK to promotions
      "promo_title": "Summer Sale",      // Joined from promotions
      "discount_amount": 660.00,
      "final_amount": 5940.00
    }
  ],
  
  "pricing_totals": {
    "total_base": 17600.00,      // Sum of all base_amounts
    "total_discount": 660.00,   // Sum of all discount_amounts
    "total_final": 5940.00      // Final amount (what guest pays)
  },
  
  // 🔥 NEW: Promo code usage tracking
  "applied_promotions": [
    {
      "promo_title": "Summer Sale",
      "promo_code_used": "SUMMER2024",
      "discount_amount": 660.00,
      "discount_type": "percentage",
      "discount_value": 10,
      "created_at": "2024-06-15 10:30:00"
    }
  ]
}
```

## 6. Visual Summary: How Tables Connect

```
┌─────────────────────────┐         ┌─────────────────────────┐
│     reservations        │         │   pricing_rules         │
│  ├─ reservation_id (PK) │◄────────┤  ├─ rule_id (PK)        │
│  ├─ reservation_code    │         │  ├─ rule_name           │
│  ├─ total_amount        │         │  ├─ rule_type           │
│  ├─ discount_amount     │         │  ├─ rule_value          │
│  └─ property_id         │         │  └─ is_active           │
└───────────┬─────────────┘         └─────────────────────────┘
            │
            │ 1:N
            ▼
┌─────────────────────────┐         ┌─────────────────────────┐
│reservation_pricing      │         │   seasonal_rates        │
│    _breakdown           │         │  ├─ season_id (PK)      │
│  ├─ breakdown_id (PK)   │◄────────┤  ├─ season_name         │
│  ├─ reservation_id (FK) │         │  ├─ rate_multiplier      │
│  ├─ res_room_id (FK)    │         │  ├─ date_start          │
│  ├─ component_type      │         │  └─ date_end            │
│  ├─ rule_id (FK) ───────┼────────►│                         │
│  ├─ season_id (FK) ─────┼────────►│                         │
│  ├─ promo_id (FK) ──────┼────────►│                         │
│  ├─ base_amount         │         │   promotions            │
│  ├─ final_amount        │◄────────┤  ├─ promo_id (PK)       │
│  └─ discount_amount     │         │  ├─ promo_title         │
└─────────────────────────┘         │  ├─ promo_code          │
                                    │  ├─ valid_from/until    │
┌─────────────────────────┐         │  └─ is_active           │
│  reservation_promotions │◄────────┘                         │
│  ├─ res_promo_id (PK)   │         └─────────────────────────┘
│  ├─ reservation_id (FK) │
│  ├─ promo_id (FK) ──────┘
│  ├─ promo_code_used     │  ← Actual code entered by guest
│  ├─ discount_amount     │
│  └─ is_applied          │
└─────────────────────────┘
```

## 7. What Problem This Solves

### BEFORE (No Connection):
```
Guest: "Why am I being charged ₱5,940?"
Staff: "Umm... that's what the system calculated?"
```

### AFTER (With New Tables):
```
Guest: "Why am I being charged ₱5,940?"
Staff: "Let me check your pricing breakdown..."

View: pricing_breakdown.php
┌─────────────────────────────────────┐
│ Component              │ Amount     │
├─────────────────────────────────────┤
│ Base Room Rate         │ ₱5,000.00  │
│ Summer Peak Season     │ ₱6,000.00  │ ← 1.2x multiplier
│ Weekend Surcharge      │ ₱6,600.00  │ ← +10%
│ Summer Sale Discount   │ -₱660.00   │ ← 10% off
├─────────────────────────────────────┤
│ TOTAL                  │ ₱5,940.00  │
└─────────────────────────────────────┘

And each line links to the exact rule that was applied!
```

## 8. Usage Tracking for Reports

```
"Which pricing rules are actually being used?"

SELECT 
  pr.rule_name,
  COUNT(pb.breakdown_id) as times_applied,
  AVG(pb.final_amount - pb.base_amount) as avg_impact
FROM pricing_rules pr
LEFT JOIN reservation_pricing_breakdown pb ON pr.rule_id = pb.rule_id
GROUP BY pr.rule_id

Result:
┌─────────────────────┬─────────────┬────────────┐
│ rule_name           │ times_applied│ avg_impact │
├─────────────────────┼─────────────┼────────────┤
│ Weekend Surcharge   │     156     │  +₱450    │
│ Holiday Rate        │      42     │  +₱1,200  │
│ Minimum Stay        │      89     │     ₱0    │
└─────────────────────┴─────────────┴────────────┘
```

---

## 9. Three-Layer Architecture Summary

### 🎯 Why Three Tables?

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                        PROMO CODE "SUMMER2024" APPLIED                       │
└─────────────────────────────────────────────────────────────────────────────┘
                                      │
        ┌─────────────────────────────┼─────────────────────────────┐
        ▼                             ▼                             ▼
┌───────────────┐            ┌─────────────────┐            ┌─────────────────┐
│ BILLING LAYER │            │ MARKETING LAYER │            │  AUDIT LAYER   │
├───────────────┤            ├─────────────────┤            ├─────────────────┤
│discount_usages│            │reservation_promo│            │pricing_breakdown│
├───────────────┤            ├─────────────────┤            ├─────────────────┤
│• Invoice gen  │            │• Campaign ROI   │            │• Price audit    │
│• Accounting   │            │• Attribution    │            │• Customer view  │
│• Tax reports  │            │• Marketing stats│            │• Support queries│
│• Revenue rec  │            │• Promo analytics│            │• Refund calc   │
└───────────────┘            └─────────────────┘            └─────────────────┘
        │                             │                             │
        └─────────────────────────────┴─────────────────────────────┘
                                      │
                                      ▼
                           ┌─────────────────────┐
                           │  WHY THIS MATTERS   │
                           ├─────────────────────┤
                           │ • Future-proof      │
                           │ • Separate concerns │
                           │ • Flexible reporting│
                           │ • Invoice-ready     │
                           │ • Marketing-ready   │
                           └─────────────────────┘
```

### 📊 Table Relationships

```
promotions (marketing campaigns)
    │
    ├─ discount_id ──→ discounts (billing rules)
    │                      │
    │                      ▼
    │              discount_usages (billing records)
    │                      │
    │                      FK: reservation_id
    │                      FK: discount_id
    │
    └─ promo_id ─────→ reservation_promotions (marketing attribution)
                            │
                            FK: reservation_id
                            FK: promo_id

reservation_pricing_breakdown (audit trail - links to ALL)
    │
    ├─ FK: reservation_id
    ├─ FK: rule_id ──────→ pricing_rules
    ├─ FK: season_id ────→ seasonal_rates
    └─ FK: promo_id ─────→ promotions
```

### ✅ Future Scenarios Handled

| Scenario | How Tables Handle It |
|----------|---------------------|
| **Invoice needs discount line item** | `discount_usages` has amount_saved + FK to discounts |
| **Marketing needs campaign ROI** | `reservation_promotions` + `promotions` join |
| **Guest questions their bill** | `pricing_breakdown` shows every component |
| **Multiple discounts stacked** | Multiple `discount_usages` rows, one `reservation_promotions` per campaign |
| **Tracking-only promo** | `reservation_promotions` without `discount_usages` |
| **Tax audit of discounts** | `discount_usages` linked to discounts + amount_saved |
| **Refund calculation** | Sum all `pricing_breakdown` components |
| **Which rules are unused?** | LEFT JOIN `pricing_rules` → `pricing_breakdown` |
