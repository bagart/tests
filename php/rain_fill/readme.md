TASK:

http://www.businessinsider.com/twitter-job-interview-question-rainfall-walls-2013-12

In Fig. 1, we have walls of different heights. Such pictures are represented by an array of integers, where the value at each index is the height of the wall. Fig. 1 is represented with an array as [2,5,1,2,3,4,7,7,6].

Now imagine it rains. How much water is going to be accumulated in puddles between walls? For example, if it rains in Fig 1, the following puddle will be formed:

No puddles are formed at edges of the wall, water is considered to simply run off the edge.

We count volume in square blocks of 1×1. Thus, we are left with a puddle between column 1 and column 6 and the volume is 10.

Write a program to return the volume for any array.


```
$ php php/rain_fill/rain_fill.php

avg time diff range2 - range1:0.42sec (LAST diff: 811.66%)
first mem diff range2 - range1: 0.01mb (0.15%)

10 test for 10000 elements with rand(0, 10000)
avg time diff range2 - range1:4.31sec (LAST diff: 38982.99%)
first mem diff range2 - range1: 0mb (0%)

10 test for 100 elements with rand(0, 100000)
avg time diff range2 - range1:0.37sec (LAST diff: 2555%)
first mem diff range2 - range1: 3.99mb (62.28%)

```


```
$ php php/rain_fill/rain_fill.php

test rain1
1111111111111111111111111111111111
test rain2
1111111111111111111111111111111111
10 test for 100000 elements with rand(0, 100)
avg time diff range2 - range1:0.42sec (LAST diff: 808.62%)
first mem diff range2 - range1: 0.01mb (0.15%)

10 test for 9999 elements with rand(0, 9999)
avg time diff range2 - range1:4.3sec (LAST diff: 44802.29%)
first mem diff range2 - range1: 0mb (0%)

10 test for 100 elements with rand(0, 100000)
avg time diff range2 - range1:0.39sec (LAST diff: 753.89%)
first mem diff range2 - range1: 3.99mb (62.28%)

```