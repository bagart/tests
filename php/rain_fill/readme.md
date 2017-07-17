TASK:

http://www.businessinsider.com/twitter-job-interview-question-rainfall-walls-2013-12

In Fig. 1, we have walls of different heights. Such pictures are represented by an array of integers, where the value at each index is the height of the wall. Fig. 1 is represented with an array as [2,5,1,2,3,4,7,7,6].

Now imagine it rains. How much water is going to be accumulated in puddles between walls? For example, if it rains in Fig 1, the following puddle will be formed:

No puddles are formed at edges of the wall, water is considered to simply run off the edge.

We count volume in square blocks of 1×1. Thus, we are left with a puddle between column 1 and column 6 and the volume is 10.

Write a program to return the volume for any array.


```
$ time php php/rain_fill/rain.php  orig basic

test rain_orig
111111111111111111111111111111111111
test rain_basic
111111111111111111111111111111111111


tests:  rain_orig VS rain_basic

1 tests for 10000 elements with rand(0, 1000)
avg time diff  rain_basic - rain_orig:0.53sec (LAST diff: 9686.1%)
first mem diff rain_basic - rain_orig: 0.05mb (5.3%)

1 tests for 10000 elements with rand(0, 10000)
avg time diff  rain_basic - rain_orig:5.2sec (LAST diff: 41203.89%)
first mem diff rain_basic - rain_orig: 0.74mb (73.11%)

1 tests for 1000 elements with rand(0, 100000)
avg time diff  rain_basic - rain_orig:5.13sec (LAST diff: 16155.8%)
first mem diff rain_basic - rain_orig: 8.69mb (496.97%)

real    0m11,123s
user    0m0,000s
sys     0m0,015s

----------------------------------------
$ time php php/rain_fill/rain.php  orig basic

test rain_orig
111111111111111111111111111111111111
test rain_basic
111111111111111111111111111111111111


tests:  rain_orig VS rain_basic

1 tests for 10000 elements with rand(0, 1000)
avg time diff  rain_basic - rain_orig:0.53sec (LAST diff: 9615.23%)
first mem diff rain_basic - rain_orig: 0.09mb (9.3%)

1 tests for 10000 elements with rand(0, 10000)
avg time diff  rain_basic - rain_orig:5.15sec (LAST diff: 44667.31%)
first mem diff rain_basic - rain_orig: 0.28mb (19.17%)

1 tests for 1000 elements with rand(0, 100000)
avg time diff  rain_basic - rain_orig:5.22sec (LAST diff: 15357.77%)
first mem diff rain_basic - rain_orig: 4.88mb (87.67%)

real    0m11,140s
user    0m0,031s
sys     0m0,015s

----------------------------------------
$ time php php/rain_fill/rain.php  orig short

test rain_orig
111111111111111111111111111111111111
test rain_short
111111111111111111111111111111111111


tests:  rain_orig VS rain_short

1 tests for 10000 elements with rand(0, 1000)
avg time diff  rain_short - rain_orig:0sec (LAST diff: 33.93%)
first mem diff rain_short - rain_orig: 0.73mb (78.41%)

1 tests for 10000 elements with rand(0, 10000)
avg time diff  rain_short - rain_orig:0sec (LAST diff: 35.87%)
first mem diff rain_short - rain_orig: 0.09mb (5.63%)

1 tests for 1000 elements with rand(0, 100000)
avg time diff  rain_short - rain_orig:0.02sec (LAST diff: 57.5%)
first mem diff rain_short - rain_orig: 0mb (0%)

real    0m0,230s
user    0m0,031s
sys     0m0,061s

----------------------------------------
$ time php php/rain_fill/rain.php  orig short

test rain_orig
111111111111111111111111111111111111
test rain_short
111111111111111111111111111111111111


tests:  rain_orig VS rain_short

1 tests for 10000 elements with rand(0, 1000)
avg time diff  rain_short - rain_orig:0sec (LAST diff: 28.34%)
first mem diff rain_short - rain_orig: 0.73mb (78.34%)

1 tests for 10000 elements with rand(0, 10000)
avg time diff  rain_short - rain_orig:0.01sec (LAST diff: 67.23%)
first mem diff rain_short - rain_orig: 0.13mb (7.95%)

1 tests for 1000 elements with rand(0, 100000)
avg time diff  rain_short - rain_orig:0sec (LAST diff: 21.37%)
first mem diff rain_short - rain_orig: 0mb (0%)

real    0m0,206s
user    0m0,000s
sys     0m0,015s

----------------------------------------
$ time php php/rain_fill/rain.php  orig short

test rain_orig
111111111111111111111111111111111111
test rain_short
111111111111111111111111111111111111


tests:  rain_orig VS rain_short

1 tests for 10000 elements with rand(0, 1000)
avg time diff  rain_short - rain_orig:0sec (LAST diff: 25.06%)
first mem diff rain_short - rain_orig: 0.73mb (77.36%)

1 tests for 10000 elements with rand(0, 10000)
avg time diff  rain_short - rain_orig:0sec (LAST diff: 35.26%)
first mem diff rain_short - rain_orig: 0.08mb (4.93%)

1 tests for 1000 elements with rand(0, 100000)
avg time diff  rain_short - rain_orig:0.02sec (LAST diff: 28.88%)
first mem diff rain_short - rain_orig: 0.04mb (0.66%)

real    0m0,290s
user    0m0,000s
sys     0m0,015s

----------------------------------------
$ time php php/rain_fill/rain.php  orig mini

test rain_orig
111111111111111111111111111111111111
test rain_mini
111111111111111111111111111111111111


tests:  rain_orig VS rain_mini

1 tests for 10000 elements with rand(0, 1000)
avg time diff  rain_mini - rain_orig:-0sec (LAST diff: -11.14%)
first mem diff rain_mini - rain_orig: 0mb (0%)

1 tests for 10000 elements with rand(0, 10000)
avg time diff  rain_mini - rain_orig:-0sec (LAST diff: -5.08%)
first mem diff rain_mini - rain_orig: 0mb (0%)

1 tests for 1000 elements with rand(0, 100000)
avg time diff  rain_mini - rain_orig:0sec (LAST diff: 6.95%)
first mem diff rain_mini - rain_orig: 0mb (0%)

real    0m0,229s
user    0m0,000s
sys     0m0,015s

----------------------------------------
$ time php php/rain_fill/rain.php  orig mini

test rain_orig
111111111111111111111111111111111111
test rain_mini
111111111111111111111111111111111111


tests:  rain_orig VS rain_mini

1 tests for 10000 elements with rand(0, 1000)
avg time diff  rain_mini - rain_orig:-0sec (LAST diff: -9.1%)
first mem diff rain_mini - rain_orig: 0mb (0%)

1 tests for 10000 elements with rand(0, 10000)
avg time diff  rain_mini - rain_orig:0sec (LAST diff: 4.61%)
first mem diff rain_mini - rain_orig: 0mb (0%)

1 tests for 1000 elements with rand(0, 100000)
avg time diff  rain_mini - rain_orig:0.01sec (LAST diff: 17.59%)
first mem diff rain_mini - rain_orig: 0mb (0%)

real    0m0,270s
user    0m0,015s
sys     0m0,015s

----------------------------------------
$ time php php/rain_fill/rain.php  mini orig

test rain_mini
111111111111111111111111111111111111
test rain_orig
111111111111111111111111111111111111


tests:  rain_mini VS rain_orig

1 tests for 10000 elements with rand(0, 1000)
avg time diff  rain_orig - rain_mini:0sec (LAST diff: 7.92%)
first mem diff rain_orig - rain_mini: 0mb (0%)

1 tests for 10000 elements with rand(0, 10000)
avg time diff  rain_orig - rain_mini:0sec (LAST diff: 1.73%)
first mem diff rain_orig - rain_mini: 0mb (0%)

1 tests for 1000 elements with rand(0, 100000)
avg time diff  rain_orig - rain_mini:-0.01sec (LAST diff: -15.7%)
first mem diff rain_orig - rain_mini: 0mb (0%)

real    0m0,269s
user    0m0,015s
sys     0m0,000s


```