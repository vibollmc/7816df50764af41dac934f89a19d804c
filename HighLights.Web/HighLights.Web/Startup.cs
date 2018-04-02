using System;
using System.Threading;
using Microsoft.AspNetCore.Builder;
using Microsoft.AspNetCore.Hosting;
using Microsoft.Extensions.Configuration;
using Microsoft.Extensions.DependencyInjection;
using HighLights.Web.Dal;
using HighLights.Web.Dal.Context;
using HighLights.Web.Dal.Implement;
using HighLights.Web.Utilities.Context;
using HighLights.Web.Utilities.Implement;
using HighLights.Web.ViewModels;
using Microsoft.AspNetCore.Mvc.ApplicationModels;
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

            services.AddMemoryCache();
            services.AddMvc()
                .AddRazorPagesOptions(options =>
                {
                    options.Conventions.Add(new GlobalTemplatePageRouteModelConvention());
                });

            services.Configure<SiteSetttings>(Configuration.GetSection("SiteSettings"));
            
            services.AddTransient<IImageServerRepository, ImageServerRepository>();
            services.AddTransient<ICrawlLinkRepository, CrawlLinkRepository>();
            services.AddTransient<IMatchRepository, MatchRepository>();

            services.AddTransient<ICrawler, Crawler>();

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

            //app.UseMvc(routes =>
            //{
            //    routes.MapRoute(
            //        name: "default",
            //        template: "{controller}/{action=Index}/{id?}");
            //});

            app.UseMvc();

            var isProcessing = false;
            _autoEvent = new AutoResetEvent(false);
            Timer = new Timer(o =>
            {
                if (isProcessing) return;
                isProcessing = true;

                var crawler = serviceProvider.GetService<ICrawler>();

                crawler.Run();

                isProcessing = false;
            }, _autoEvent, 1000, 240000);
        }
    }

    public class GlobalTemplatePageRouteModelConvention
        : IPageRouteModelConvention
    {
        public void Apply(PageRouteModel model)
        {
            var selectorCount = model.Selectors.Count;
            for (var i = 0; i < selectorCount; i++)
            {
                var selector = model.Selectors[i];
                model.Selectors.Add(new SelectorModel
                {
                    AttributeRouteModel = new AttributeRouteModel
                    {
                        Order = -1,
                        Template = AttributeRouteModel.CombineTemplates(
                            selector.AttributeRouteModel.Template,
                            "{globalTemplate?}"),
                    }
                });
            }
        }
    }
}
