using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading;
using System.Threading.Tasks;
using Football.Show.Dal;
using Football.Show.Dal.Context;
using Football.Show.Dal.Implement;
using Football.Show.Utilities;
using Football.Show.Utilities.Context;
using Football.Show.Utilities.Implement;
using Football.Show.ViewModels;
using Microsoft.AspNetCore.Builder;
using Microsoft.AspNetCore.Hosting;
using Microsoft.AspNetCore.Http;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Configuration;
using Microsoft.Extensions.DependencyInjection;

namespace Football.Show
{
    public class Startup
    {
        public Startup(IConfiguration configuration)
        {
            Configuration = configuration;
        }

        public IConfiguration Configuration { get; }
        private Timer _timer;
        private AutoResetEvent _autoEvent;
        private bool _isProcessing = false;

        // This method gets called by the runtime. Use this method to add services to the container.
        public void ConfigureServices(IServiceCollection services)
        {
            

            services.Configure<CookiePolicyOptions>(options =>
            {
                // This lambda determines whether user consent for non-essential cookies is needed for a given request.
                options.CheckConsentNeeded = context => true;
                options.MinimumSameSitePolicy = SameSiteMode.None;
            });

            

            services.AddMvc().SetCompatibilityVersion(CompatibilityVersion.Version_2_1);

            services.AddDbContext<MainDbContext>(options => options.UseMySql(Configuration.GetConnectionString("MySqlConnection")));

            services.Configure<SiteSetttings>(Configuration.GetSection("SiteSettings"));

            services.AddTransient<IImageServerRepository, ImageServerRepository>();
            services.AddTransient<ICrawlLinkRepository, CrawlLinkRepository>();
            services.AddTransient<IMatchRepository, MatchRepository>();
            services.AddTransient<IFtpHelper, FtpHelper>();
            services.AddTransient<ICrawler, Crawler>();
            services.AddTransient<LoadDbContext, LoadDbContext>();

            services.AddSingleton<AutoJob, AutoJob>();
        }

        // This method gets called by the runtime. Use this method to configure the HTTP request pipeline.
        public void Configure(IApplicationBuilder app, IHostingEnvironment env, IServiceProvider services)
        {
            if (env.IsDevelopment())
            {
                app.UseDeveloperExceptionPage();
            }
            else
            {
                app.UseExceptionHandler("/Error");
            }

            app.UseStaticFiles();
            app.UseCookiePolicy();

            app.UseMvc();

            _autoEvent = new AutoResetEvent(false);
            _timer = new Timer(o =>
            {
                if (_isProcessing) return;
                _isProcessing = true;
                using (var serviceScope = app.ApplicationServices.CreateScope())
                {
                    var autoJob = serviceScope.ServiceProvider.GetService<ICrawler>();// services.GetService<AutoJob>();
                    autoJob.Run();
                    //autoJob.ExecuteCrawlContent();
                }

                _isProcessing = false;
            }, _autoEvent, 1000, 2400000);
        }
    }
}
