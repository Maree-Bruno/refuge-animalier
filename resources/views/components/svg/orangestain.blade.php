<svg xmlns="http://www.w3.org/2000/svg" width="1440" height="316" viewBox="0 0 1440 316"
     fill="none"{{$attributes->class('absolute inset-0 -z-10 w-full h-full min-w-full')}}>
    <defs>
        <filter id="glassAdvanced" x="-50%" y="-50%" width="200%" height="200%">
            <feGaussianBlur in="SourceGraphic" stdDeviation="10" result="blur"/>
            <feColorMatrix in="blur" type="saturate" values="1.3" result="saturate"/>
            <feComponentTransfer in="saturate" result="bright">
                <feFuncR type="linear" slope="1.1" intercept="0.05"/>
                <feFuncG type="linear" slope="1.1" intercept="0.05"/>
                <feFuncB type="linear" slope="1.1" intercept="0.05"/>
                <feFuncA type="linear" slope="0.8"/>
            </feComponentTransfer>
        </filter>
    </defs>
    <path
        d="M1009.09 119.585C1120.5 51.7032 1092.52 40.5662 1229.32 23.3306C1366.13 6.09503 1483.45 16.0651 1519.51 98.3719C1564.6 201.255 1405.51 308.117 1057.28 217.43C709.05 126.744 671.221 134.965 467.569 251.637C263.917 368.309 56.1185 302.283 -45.9668 241.826C-148.052 181.368 -127.324 70.7949 56.1185 23.3306C239.561 -24.1336 378.266 9.71887 467.569 46.1347C574.836 70.0878 897.674 187.467 1009.09 119.585Z"
        fill="#F7A072" fill-opacity="0.3" filter="url(#glassAdvanced)"/>
</svg>
