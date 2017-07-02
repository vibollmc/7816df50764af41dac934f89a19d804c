using System;
using System.Collections.Generic;
using System.Globalization;
using System.Linq;
using System.Threading.Tasks;

namespace JavCrawl.Utility
{
    public static class ExtensionFuncitons
    {
        public static string ToTitleCase(this string str)
        {
            var arr = str.ToLower().Split(' ');
            var results = string.Empty;
            foreach(var s in arr)
            {
                if (string.IsNullOrWhiteSpace(s)) continue;

                results += s.First().ToString().ToUpper() + s.Substring(1) + " ";
            }

            return results.Trim();
        }

        public static int UnixTicks(this DateTime dt)
        {
            var d1 = new DateTime(1970, 1, 1);
            var d2 = dt.ToUniversalTime();
            var ts = new TimeSpan(d2.Ticks - d1.Ticks);
            return (int)(ts.TotalMilliseconds/1000);
        }

        public static int UnixTicks(this string str)
        {
            return str.UnixTicks("yyyy-MM-dd");
        }

        public static int UnixTicks(this string str, string format)
        {
            DateTime outd;
            if (DateTime.TryParseExact(str, format, CultureInfo.InvariantCulture, DateTimeStyles.None, out outd))
            {
                return outd.UnixTicks();
            }

            return 0;
        }
    }
}
