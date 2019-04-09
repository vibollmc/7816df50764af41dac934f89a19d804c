using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Configuration;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace Football.Show.Dal
{
    public class LoadDbContext
    {
        public MainDbContext DbContext;

        public LoadDbContext(IConfiguration configuration)
        {
            var builder = new DbContextOptionsBuilder<MainDbContext>();
            builder.UseMySql(configuration.GetConnectionString("MySqlConnection"));

            DbContext = new MainDbContext(builder.Options);
        }
    }
}
