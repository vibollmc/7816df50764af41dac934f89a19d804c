using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Threading.Tasks;
using Football.Show.ViewModels;
using Microsoft.AspNetCore;
using Microsoft.AspNetCore.Hosting;
using Microsoft.Extensions.Configuration;
using Microsoft.Extensions.Logging;

namespace Football.Show
{
    public class Program
    {
        public static void Main(string[] args)
        {
            var config = new ConfigurationBuilder().AddJsonFile("hostings.json", optional: true).Build();

            var hostingSettings = new HostingSettings();
            config.GetSection("HostingSettings").Bind(hostingSettings);

            var builder = CreateWebHostBuilder(args);

            if (!string.IsNullOrWhiteSpace(hostingSettings.Url))
            {
                Console.WriteLine(hostingSettings.Url);

                builder.UseUrls(hostingSettings.Url);
            }   

            if (!string.IsNullOrWhiteSpace(hostingSettings.WebRoot))
            {
                Console.WriteLine(hostingSettings.WebRoot);

                builder.UseWebRoot(hostingSettings.WebRoot);
            }
            
            // Run builder
            builder.Build().Run();
        }

        public static IWebHostBuilder CreateWebHostBuilder(string[] args) =>
            WebHost.CreateDefaultBuilder(args)
                .UseStartup<Startup>();
    }
}
