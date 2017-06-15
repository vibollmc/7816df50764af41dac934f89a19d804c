using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Builder;
using Microsoft.AspNetCore.Hosting;
using Microsoft.Extensions.Configuration;
using Microsoft.Extensions.DependencyInjection;
using Microsoft.Extensions.Logging;
using Microsoft.EntityFrameworkCore;
using JavCrawl.Dal;
using JavCrawl.Dal.Context;
using JavCrawl.Dal.Implement;
using JavCrawl.Utility.Context;
using JavCrawl.Utility.Implement;
using JavCrawl.Utility;
using System.Threading;
using Microsoft.AspNetCore.HttpOverrides;
using JavCrawl.Models;

namespace JavCrawl
{
    public class Startup
    {
        public Startup(IHostingEnvironment env)
        {
            var builder = new ConfigurationBuilder()
                .SetBasePath(env.ContentRootPath)
                .AddJsonFile("appsettings.json", optional: false, reloadOnChange: true)
                .AddJsonFile($"appsettings.{env.EnvironmentName}.json", optional: true)
                .AddEnvironmentVariables();
            Configuration = builder.Build();
        }

        public IConfigurationRoot Configuration { get; }
        public Timer Timer;
        private AutoResetEvent _autoEvent;
        // This method gets called by the runtime. Use this method to add services to the container.
        public void ConfigureServices(IServiceCollection services)
        {
            // Add framework services.
            services.AddMvc();

            services.AddDbContext<MySqlContext>(options =>
            options.UseMySql(Configuration.GetConnectionString("MySqlConnection")));

            services.AddTransient<IHtmlHelper, HtmlHelper>();
            services.AddTransient<IFtpHelper, FtpHelper>();
            services.AddTransient<IDbRepository, DbRepository>();
            services.AddTransient<JobCrawl, JobCrawl>();
        }

        // This method gets called by the runtime. Use this method to configure the HTTP request pipeline.
        public void Configure(IApplicationBuilder app, IHostingEnvironment env, IServiceProvider serviceProvider, ILoggerFactory loggerFactory)
        {
            loggerFactory.AddConsole(Configuration.GetSection("Logging"));
            loggerFactory.AddDebug();
            
            if (env.IsDevelopment())
            {
                app.UseDeveloperExceptionPage();
                app.UseBrowserLink();
            }
            else
            {
                app.UseExceptionHandler("/Home/Error");
            }

            app.UseForwardedHeaders(new ForwardedHeadersOptions
            {
                ForwardedHeaders = ForwardedHeaders.XForwardedFor | ForwardedHeaders.XForwardedProto
            });

            app.UseStaticFiles();

            app.UseMvc(routes =>
            {
                routes.MapRoute(
                    name: "default",
                    template: "{controller=Home}/{action=Index}/{id?}");
            });
            
            var timerSetting = Configuration.GetSection("TimerSettings").Get<TimerSettings>();
            if (timerSetting != null && timerSetting.Enabled)
            {
                _autoEvent = new AutoResetEvent(false);
                var isProcessing = false;

                Timer = new Timer((o) =>
                {
                    if (isProcessing) return;
                    isProcessing = true;
                    var crawler = serviceProvider.GetService<JobCrawl>();
                    crawler.Execute();
                    isProcessing = false;

                }, _autoEvent, 1000, timerSetting.Interval);

            }

        }
    }
}
