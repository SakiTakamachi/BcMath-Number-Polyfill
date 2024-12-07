--TEST--
round() - HalfAwayFromZero
--FILE--
<?php
require_once __DIR__ . '/round.inc';
run_round_test(RoundingMode::HalfAwayFromZero);
?>
--EXPECT--
========== precision is -3 ==========
         0:  0
         4:  0
        -5:  0
         6:  0
     -0.04:  0
      0.05:  0
     -0.06:  0
 0.0050001:  0
     0.005:  0
-0.0050010:  0
 0.0050001:  0
-0.0050000:  0
       150:  0
      -150:  0
   150.000:  0
  -150.001:  0

========== precision is -2 ==========
         0:  0
         4:  0
        -5:  0
         6:  0
     -0.04:  0
      0.05:  0
     -0.06:  0
 0.0050001:  0
     0.005:  0
-0.0050010:  0
 0.0050001:  0
-0.0050000:  0
       150:  200
      -150: -200
   150.000:  200
  -150.001: -200

========== precision is -1 ==========
         0:  0
         4:  0
        -5: -10
         6:  10
     -0.04:  0
      0.05:  0
     -0.06:  0
 0.0050001:  0
     0.005:  0
-0.0050010:  0
 0.0050001:  0
-0.0050000:  0
       150:  150
      -150: -150
   150.000:  150
  -150.001: -150

========== precision is 0 ==========
         0:  0
         4:  4
        -5: -5
         6:  6
     -0.04:  0
      0.05:  0
     -0.06:  0
 0.0050001:  0
     0.005:  0
-0.0050010:  0
 0.0050001:  0
-0.0050000:  0
       150:  150
      -150: -150
   150.000:  150
  -150.001: -150

========== precision is 1 ==========
         0:  0.0
         4:  4.0
        -5: -5.0
         6:  6.0
     -0.04:  0.0
      0.05:  0.01
     -0.06: -0.01
 0.0050001:  0.0
     0.005:  0.0
-0.0050010:  0.0
 0.0050001:  0.0
-0.0050000:  0.0
       150:  150.0
      -150: -150.0
   150.000:  150.0
  -150.001: -150.0

========== precision is 2 ==========
         0:  0.00
         4:  4.00
        -5: -5.00
         6:  6.00
     -0.04: -0.04
      0.05:  0.05
     -0.06: -0.06
 0.0050001:  0.001
     0.005:  0.001
-0.0050010: -0.001
 0.0050001:  0.001
-0.0050000: -0.001
       150:  150.00
      -150: -150.00
   150.000:  150.00
  -150.001: -150.00

========== precision is 3 ==========
         0:  0.000
         4:  4.000
        -5: -5.000
         6:  6.000
     -0.04: -0.040
      0.05:  0.050
     -0.06: -0.060
 0.0050001:  0.005
     0.005:  0.005
-0.0050010: -0.005
 0.0050001:  0.005
-0.0050000: -0.005
       150:  150.000
      -150: -150.000
   150.000:  150.000
  -150.001: -150.001

========== precision is 5 ==========
         0:  0.00000
         4:  4.00000
        -5: -5.00000
         6:  6.00000
     -0.04: -0.04000
      0.05:  0.05000
     -0.06: -0.06000
 0.0050001:  0.00500
     0.005:  0.00500
-0.0050010: -0.00500
 0.0050001:  0.00500
-0.0050000: -0.00500
       150:  150.00000
      -150: -150.00000
   150.000:  150.00000
  -150.001: -150.00100

========== precision is 10 ==========
         0:  0.0000000000
         4:  4.0000000000
        -5: -5.0000000000
         6:  6.0000000000
     -0.04: -0.0400000000
      0.05:  0.0500000000
     -0.06: -0.0600000000
 0.0050001:  0.0050001000
     0.005:  0.0050000000
-0.0050010: -0.0050010000
 0.0050001:  0.0050001000
-0.0050000: -0.0050000000
       150:  150.0000000000
      -150: -150.0000000000
   150.000:  150.0000000000
  -150.001: -150.0010000000
