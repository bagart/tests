
function func(s, a, b)
{
    var match_empty=/^$/ ;
    if (s.match(match_empty))
    {
        return -1;
    } else {
		var i=s.length-1;
		var aIndex=-1;
		var bIndex=-1;

		while ((aIndex==-1) && (bIndex==-1) && (i>=0))
		{
			if (s.substring(i, i+1) == a)
				aIndex=i;
			if (s.substring(i, i+1) == b)
				bIndex=i;
			i--;
		}

		if (aIndex != -1)
		{
			if (bIndex == -1)
				return aIndex;
			else
				return Math.max(aIndex, bIndex);
		}
		else
		{
			if (bIndex != -1)
				return bIndex;       
		  else
				return -1;
		}
	}
}

	
function func1(s, a, b) {
	return Math.max(
		s.lastIndexOf((''+a).length == 1 ? a : null),
		s.lastIndexOf((''+b).length == 1 ? b : null)
	);
}

var test = [];
test.push(func('10001', '1','0') == func1('10001', '1', '0'))
test.push(func('10001', '0','1') == func1('10001', '0', '1'))
test.push(func('10001', '10','2') == func1('10001', '10','2'))
test.push(func('10001', '2','10') == func1('10001', '2','10'))
test.push(func('10001', 1,0) == func1('10001', 1, 0))
test.push(func('10001', 0,1) == func1('10001', 0, 1))
test.push(func('10001', 10,2) == func1('10001', 10,2))
test.push(func('10001', 2,10) == func1('10001', 2,10))
console.log(test)