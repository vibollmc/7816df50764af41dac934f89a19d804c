using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Net;
using System.Text;
using System.Threading.Tasks;
using MaxMind.Db;
using MaxMind.GeoIP2;
using Newtonsoft.Json;
using NodaTime;
using NodaTime.TimeZones;
using TimeZoneConverter;

namespace SplitSSH
{
    public class LolBotData
    {
        public int Id { get; set; }
        public string Server { get; set; }
        public string User { get; set; }
        public string Password { get; set; }
        public int Type { get; set; }
        public string Country { get; set; }
        public string WZoneId { get; set; }
        public string IanaZone { get; set; }
        public string Offset { get; set; }
        public string Status { get; set; }
        public int Used { get; set; }
    }
    public class IpApiResult
    {
        public string Country { get; set; }
        public string Timezone { get; set; }
        public string WZoneid { get; set; }
        public string Offset { get; set; }
    }
    public static class BotHelper
    {
        //http://ip-api.com/json/1.161.165.44

        private static TzdbDateTimeZoneSource _tzdbDateTimeZoneSource;

        public static IpApiResult GetInfo(string ip)
        {
            try
            {
                var result = new IpApiResult();

                using (var reader = new DatabaseReader($"{AppDomain.CurrentDomain.BaseDirectory}GeoDb\\GeoLite2-City.mmdb"))
                {
                    var city = reader.City(ip);
                    if (city == null) return null;
                    result.Country = city.Country.IsoCode;
                    result.Timezone = city.Location.TimeZone;

                    result.WZoneid = TZConvert.IanaToWindows(result.Timezone);
                    result.Offset = GetOffset(result.Timezone);
                }

                return result;
            }
            catch (Exception ex)
            {
                return null;
            }
        }

        private static string GetOffset(string timezone)
        {
            var result = "0";
            if (_tzdbDateTimeZoneSource == null)  
                using (var stream = File.OpenRead($"{AppDomain.CurrentDomain.BaseDirectory}GeoDb\\tzdb2019c.nzd"))
                {
                    _tzdbDateTimeZoneSource = TzdbDateTimeZoneSource.FromStream(stream);
                }

            var zonedDateTime = _tzdbDateTimeZoneSource.ForId(timezone)
                .AtStrictly(new LocalDateTime(DateTime.Now.Year, DateTime.Now.Month, DateTime.Now.Day, DateTime.Now.Hour, DateTime.Now.Minute));

            result = TimeSpan.FromMilliseconds(zonedDateTime.Offset.Milliseconds).TotalHours.ToString();

            return result;
        }
    }
}
