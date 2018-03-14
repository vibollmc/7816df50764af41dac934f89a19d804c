using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Builder;
using Microsoft.AspNetCore.Hosting;
using Microsoft.Extensions.Configuration;
using Microsoft.Extensions.DependencyInjection;
using HighLights.Web.Dal;
using HighLights.Web.Dal.Context;
using HighLights.Web.Dal.Implement;
using HighLights.Web.Utilities.Context;
using HighLights.Web.Utilities.Implement;
using Microsoft.EntityFrameworkCore;

namespace HighLights.Web
{
    public class Startup
    {
        public Startup(IConfiguration configuration)
        {
            Configuration = configuration;
        }

        public IConfiguration Configuration { get; }
        public Timer Timer;
        private AutoResetEvent _autoEvent;

        // This method gets called by the runtime. Use this method to add services to the container.
        public void ConfigureServices(IServiceCollection services)
        {
            services.AddDbContext<HighLightsContext>(options => options.UseMySql(Configuration.GetConnectionString("MySqlConnection")));
            services.AddMvc();

            services.AddTransient<ICrawler, Crawler>();

            services.AddTransient<IImageServerRepository, ImageServerRepository>();
            services.AddTransient<ICrawlLinkRepository, CrawlLinkRepository>();

            services.AddTransient<IFtpHelper, FtpHelper>();
        }

        // This method gets called by the runtime. Use this method to configure the HTTP request pipeline.
        public void Configure(IApplicationBuilder app, IHostingEnvironment env, IServiceProvider serviceProvider)
        {
            if (env.IsDevelopment())
            {
                app.UseDeveloperExceptionPage();
                app.UseBrowserLink();
            }
            else
            {
                app.UseExceptionHandler("/Error");
            }

            app.UseStaticFiles();

            app.UseMvc(routes =>
            {
                routes.MapRoute(
                    name: "default",
                    template: "{controller}/{action=Index}/{id?}");
            });

            var isProcessing = false;
            _autoEvent = new AutoResetEvent(false);
            Timer = new Timer(o =>
            {
                if (isProcessing) return;
                isProcessing = true;

                var crawler = serviceProvider.GetService<ICrawler>();

                crawler.Run();

            }, _autoEvent, 1000, 120000);
        }
    }
}
