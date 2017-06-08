using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Mvc;
using JavCrawl.Utility.Context;
using JavCrawl.Dal.Context;
using JavCrawl.Models;

namespace JavCrawl.Controllers
{
    public class HomeController : Controller
    {
        private readonly IHtmlHelper _htmlHelper;
        private readonly IDbRepository _dbRepository;
        public HomeController(IHtmlHelper htmlHelper, IDbRepository dbRepository)
        {
            _htmlHelper = htmlHelper;
            _dbRepository = dbRepository;
        }
        public async Task<IActionResult> Index(string link)
        {
            var model = new JavHiHiMovies();
            var number = 0;
            var err = string.Empty;
            if (!string.IsNullOrWhiteSpace(link))
            {
                try
                {
                    model = await _htmlHelper.GetJavHiHiMovies(link);

                    number = await _dbRepository.CrawlJavHiHiMovies(model);

                }
                catch(Exception ex)
                {
                    err = ex.Message;
                }
            }
            ViewBag.Number = number;
            ViewBag.Err = err;

            return View(model);
        }

        public IActionResult About()
        {
            ViewData["Message"] = "Your application description page.";

            return View();
        }

        public IActionResult Contact()
        {
            ViewData["Message"] = "Your contact page.";

            return View();
        }

        public IActionResult Error()
        {
            return View();
        }
    }
}
